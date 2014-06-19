<?php

namespace Maxim\AdminBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Yaml\Yaml;

class AdminController extends Controller
{
    public function indexAction()
    {
    	//DASHBOARD, LOAD STATISTICS
    	
    	# -----------
    	#  Registers
    	# -----------
    	$em = $this->getDoctrine()->getManager();
		$qb = $em->createQueryBuilder()
			      ->select('COUNT(u) as amount')
			      ->from('MaximCMSBundle:User', 'u')
				  ->where('u.registeredOn > :param');
				  
		$qb->setParameters(array("param" => (new \DateTime(date('Y-m-d h:i:s', strtotime(date('Y-m-d h:i:s') . ' -1 day'))))));
		$registers = $qb->getQuery()->getResult();
		
		
		# ------------------
		#  Verified Accounts
		# ------------------
		//$qb = $em->createQueryBuilder()
			     // ->select('COUNT(ua) as amount')
			     // ->from('MaximCMSBundle:Useraccounts', 'ua');
		//$useraccounts = $qb->getQuery()->getResult();
		
		
		# ---------
		#  MODULES 
		# ---------
		$modules = $em->getRepository('MaximCMSBundle:Module')->findAll();
        $data['modules'] = array();
		foreach($modules as $module)
        {
            $data['modules'][] = array(
                "name"          => $module->getName(),
                "description"   => $module->getDescription(),
                "author"        => $module->getAuthor(),
                "version"       => $module->getVersion(),
                "id"            => $module->getId(),
                "activated"     => $module->getActivated(),
                "hasConfig"     => $this->hasConfig($module->getName()),
                "hasAcp"        => $this->hasAcp($module->getName())
            );
        }
		$data['registers']    = $registers[0];
		//$data['useraccounts'] = $useraccounts[0];
		
        return $this->render('MaximAdminBundle:Default:index.html.twig', $data);
    }
    public function hasAcp($file)
    {
        $path = __dir__.'/Module/'.$file.'/admin/'.$file.'AdminController.php';
        if(!file_exists($path)){ return false; }
        if(file_get_contents($path) == ""){ return false; }
        return true;
    }
    public function hasConfig($file)
    {
        $path = __dir__.'/Module/'.$file.'/'.$file.'Config.yml';
        if(!file_exists($path)){ return false; }
        if(file_get_contents($path) == ""){ return false; }
        return true;
    }
    public function widgetAction($widget, $value = null)
    {
        switch($widget)
        {
            case "server":
                $data['value'] = $value;
                $data['servers']  = $this->getDoctrine()->getRepository('MaximCMSBundle:Server')->findAll();
                return new Response($this->renderView('MaximAdminBundle:Widgets:server.html.twig', $data));
            case "news_section":
                $data['value'] = $value;
                $data['sections']  = $this->getDoctrine()->getRepository('MaximCMSBundle:NewsSection')->findAll();
                return new Response($this->renderView('MaximAdminBundle:Widgets:news_section.html.twig', $data));
        }
        return new Response("");
    }
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();
        //SELECT news.id, news.title, news.content, news.date, news.user_id, COUNT(comment.id) as comments FROM news LEFT JOIN comment ON news.id = comment.newsId GROUP BY news.id
        $stmt = $em->getConnection()
            ->prepare('SELECT purchase.amount as amount, purchase.date as date, purchase.name as name, purchase.transaction as transaction, shop.name as itemname FROM purchase INNER JOIN shop ON purchase.shop_id = shop.id WHERE (MONTH(date) = MONTH(NOW()) AND (YEAR(date) = YEAR(NOW()))) ORDER BY date ASC');
        $stmt->execute();
        $data = $stmt->fetchAll();

        $filename = "purchases_".date("Y_m_d_His").".csv";
        $response = $this->render('MaximAdminBundle:admin:file_export.html.twig', array('data' => $data));
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename='.$filename);
        return $response;
    }
    public function editAction($file)
    {
        $logger  = $this->container->get('logger');

        #They can only edit js, css and yml files
        $check = explode('.', $file);

        $formats = array("yml", "js", "css", "twig.html", "twig", "html");
        $format_details = array("yml" => "yaml", "js" => "javascript", "twig.html" => "html", "twig" => "html", "html" => "html", "css" => "css");

        #get file extension
        $ext = pathinfo($file, PATHINFO_EXTENSION);

        $content = FALSE;
        try
        {
            if(in_array($ext, $formats))
            {
                # OPEN THE FILE IN AN EDITOR, the code only!
                # default dir: src/Maxim/CMSBundle
                $content = file_get_contents(__dir__.'/../../CMSBundle/'.$file);
            }
        }
        catch(\Exception $ex)
        {
            $logger->error('[FILE EDIT]: '.$ex->getMessage());
            $logger->error('[FILE EDIT]: '.$ex->getMessage());
        }
        $data['content'] = ($content != FALSE) ? $content : "Could not load file: ".$file;
        $data["format"] = (isset($format_details[$ext]) ? $format_details[$ext] : false);
        $data['file'] = $file;

        return new Response($this->renderView('MaximAdminBundle:Default:file_editor.html.twig', $data));
    }
    public function saveAction($file)
    {
        $logger  = $this->container->get('logger');
        try
        {
            $request = $this->getRequest();
            # location
            $location = __dir__.'/../'.$file;
            file_put_contents($location, $request->request->get('_admin_file_content'));
        }
        catch(\Exception $ex)
        {
            $logger->error('[FILE EDIT]: '.$ex->getMessage());
            return new Response(json_encode(array("success" => false, "message" => "Error editing file")));
        }

        return new Response(json_encode(array("success" => true, "message" => "file saved succesfuly")));
    }

	public function visitorsAction()
	{
		return $this->render('MaximCMSBundle:admin:visitors.html.twig');
	}
	
	public function mailAction()
	{
		$request = $this->getRequest();
		$data = array();
		if($request->query->get('to'))
		{
			$data['email'] = array("to" => $request->query->get('to'));
		}
		
		return $this->render('MaximAdminBundle:admin:email.html.twig', $data);
	}
	public function mailSendAction()
	{
		$logger  = $this->container->get('logger');
		$request = $this->getRequest();
		
		$to      = $request->request->get('_admin_mail_to');
		$from    = $request->request->get('_admin_mail_from');
		$subject = $request->request->get('_admin_mail_subject');
		$body    = $request->request->get('_admin_mail_body');
		
		try
		{
			$message = \Swift_Message::newInstance()
					  	 ->setSubject($subject)
					     ->setFrom($from)
					     ->setTo($to)
					     ->setBody($this->renderView('MaximAdminBundle:email:plain.html.twig', array('message' => $body )))
						 ->setContentType("text/html")
					;
			$this->get('mailer')->send($message);
			$result = array('success' => true, 'message' => 'mail sended succesfuly');
		}
		catch(\Exception $ex)
		{
			//error while sending mail
			$logger->error('ADMIN: Could not send mail: '.$ex->getMessage());
			$result = array('success' => false, 'message' => 'error while sending mail');
		}
	    
		return new Response(json_encode($result));		
	}
	


}

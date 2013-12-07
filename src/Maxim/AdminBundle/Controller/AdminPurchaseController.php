<?php
/**
 * Project: MCSuite
 * File: AdminPurchaseController.php
 * User: Maxim
 * Date: 27/04/13
 * Time: 19:59
 */

namespace Maxim\AdminBundle\Controller;
use Maxim\CMSBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Yaml\Yaml;
use LanKit\DatatablesBundle\Datatables\DataTable;
use Maxim\CMSBundle\Entity\Purchase;

class AdminPurchaseController extends Controller{

    public function purchaseAction()
    {
        $data['date'] = date("F");
        return $this->render('MaximAdminBundle:Default:purchases.html.twig', $data);
    }

    public function purchaseJsonAction()
    {
        $em = $this->getDoctrine()->getManager();
        //SELECT news.id, news.title, news.content, news.date, news.user_id, COUNT(comment.id) as comments FROM news LEFT JOIN comment ON news.id = comment.newsId GROUP BY news.id
        $stmt = $em->getConnection()
            ->prepare('SELECT SUM(amount) as amount, date FROM purchase WHERE MONTH(date) = MONTH(NOW()) GROUP BY DATE_FORMAT(date,"%d-%F-%y") ORDER BY date ASC');
        $stmt->execute();
        $purchases = $stmt->fetchAll();
        $amount = array();
        $total = 0;

        foreach($purchases as $purchase)
        {
            $amount[] = array(date('d-F-y', strtotime($purchase['date'])), (int)$purchase['amount']);
            $total += $purchase['amount'];
        }

        $response = new Response(json_encode($amount));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
<?php
/**
 * Author: Maxim
 * Date: 19/06/2014
 * Time: 14:17
 * Property of MCSuite
 */
namespace Maxim\CMSBundle\EventListener;

use Oneup\UploaderBundle\Event\PostPersistEvent;

class UploadListener
{
    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function onUpload(PostPersistEvent $event)
    {
        $request = $event->getRequest();
        $gallery = $request->get('gallery');
    }
} 
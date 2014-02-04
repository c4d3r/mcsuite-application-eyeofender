<?php
/**
 * Author: Maxim
 * Date: 28/12/13
 * Time: 15:17
 * Property of MCSuite
 */

namespace Maxim\Theme\Admin\NotepadBundle;


use Symfony\Component\HttpKernel\Bundle\Bundle;

class MaximThemeAdminNotepadBundle extends Bundle
{
    public function getParent()
    {
        return 'SonataAdminBundle';
    }
} 
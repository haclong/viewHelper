<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CoffeeBar\Form\Helper ;

use Zend\Form\View\Helper\FormCollection ;
use Zend\Form\Element\Collection ;

class MenuItemFormCollection extends FormCollection
{
    public function renderTemplate(Collection $collection)
    {
        $elementHelper          = $this->getElementHelper();
        $escapeHtmlAttribHelper = $this->getEscapeHtmlAttrHelper();
        $fieldsetHelper         = $this->getFieldsetHelper();
        $element                = $collection->getTemplateElement();
        
        if ($element instanceof FieldsetInterface) {
            $templateMarkup .= $fieldsetHelper($element);
        }

        $formRow          = $this->view->plugin('FormRow') ;

        $templateMarkup         = '';
        $templateMarkup  .= '<fieldset class="form-inline">' ;
        $templateMarkup  .= $formRow($element->get('id')) . '&nbsp;&nbsp;';
        $templateMarkup  .= $formRow($element->get('number')) ;
        $templateMarkup  .= '</fieldset>' ;

        return sprintf(
            $this->templateWrapper,
            $escapeHtmlAttribHelper($templateMarkup)
        );
    }
}

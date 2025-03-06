<?php

namespace App\Utils;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;

class FormUtils
{
  public static function generateErrorsArrayFromForm(FormInterface $form)
  {
    $formName = $form->getName();
    $errors = [];

    /** @var FormError $formError */
    foreach ($form->getErrors(true, true) as $formError) {
      $name = '';
      $thisField = $formError->getOrigin()->getName();
      $origin = $formError->getOrigin();
      while ($origin = $origin->getParent()) {
        if ($formName !== $origin->getName()) {
          $name = $origin->getName() . '_' . $name;
        }
      }

      $fieldName = $name . $thisField;

      if (!in_array($fieldName, $errors)) {
        $errors[$fieldName] = [];
      }
      $errors[$fieldName][] = $formError->getMessage();
    }

    return $errors;
  }
}

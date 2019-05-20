<?php
/**
 * Created by PhpStorm.
 * User: adeli
 * Date: 20/05/2019
 * Time: 10:52
 */

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ArticleSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //parent::buildForm($builder, $options);

        $builder->add('searchField');
    }

}
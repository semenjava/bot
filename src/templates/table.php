<?php $this->extends('base.php');?>

<?=$this->header?>

<title><?=$this->data['title']?></title>

<?=$this->form->startForm?>

<?=$this->form->label?>
<?=$this->form->textField?>
<?=$this->form->submit?>

<?=$this->form->endForm?>

<?=$this->footer?>


<form action="" method="post">
<?php
$form = new Form($_POST);
echo $form->inputName('user');
echo $form->inputPassword('pass');
echo $form->submit();
var_dump($_POST);
?>
</form>



<?php


class Form{

    private $data;

    public function __construct($data=array())
    {
        $this->data = $data;
    }

    public function inputName($name)
    {
        return "<input type='text' name='{$name}'></input><br>" ;

    }

    public function inputPassword($name)
    {
        return "<input type='password' name='{$name}'></input><br>" ;

    }

    public function submit()
    {
        return "(<button type='submit'> Envoyer </button>)" ;
    }
}
?>
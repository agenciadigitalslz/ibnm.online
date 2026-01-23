<?php
    move_uploaded_file($_FILES['photo']['tmp_name'], '../../sistema/img/contas/' . $_FILES['photo']['name']);
?>
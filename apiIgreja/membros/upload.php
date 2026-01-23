<?php
    move_uploaded_file($_FILES['photo']['tmp_name'], '../../sistema/img/membros/' . $_FILES['photo']['name']);
?>
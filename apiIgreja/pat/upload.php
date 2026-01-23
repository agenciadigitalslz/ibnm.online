<?php
    move_uploaded_file($_FILES['photo']['tmp_name'], '../../sistema/img/patrimonios/' . $_FILES['photo']['name']);
?>
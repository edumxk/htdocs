<?php

$str = "";
for($i = 0; $i<3; $i++){
    $str .= "<tr>
        <td style=\"text-align: center; border: solid black 1px\">".$i."</td>
        <td style=\"text-align: center; border: solid black 1px\">".$i."</td>
        <td style=\"text-align: center; border: solid black 1px\">".$i."</td>
        <td style=\"text-align: center; border: solid black 1px\">".$i."</td>
        <td style=\"text-align: center; border: solid black 1px\">".$i."</td>
    </tr>";
}

echo $str;



?>
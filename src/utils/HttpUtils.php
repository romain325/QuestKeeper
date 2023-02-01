<?php

function getJSONBody() : array {
    return json_decode(file_get_contents("php://input"),true);
}
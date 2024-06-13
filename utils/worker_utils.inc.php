<?php

function timestamp(): string {
    return '['. \Safe\date('Y/m/d--G:i:s') . '] ';
}

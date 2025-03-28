<?php
require __DIR__ . '/vendor/autoload.php';
$books = [
    ['ISBN', 'title', 'author', 'publisher', 'ctry' ],
    [618260307, 'The Hobbit', 'J. R. R. Tolkien', 'Houghton Mifflin', 'USA'],
    [908606664, 'Slinky Malinki', 'Lynley Dodd', 'Mallinson Rendel', 'NZ']
];

$xlsx = Shuchkin\SimpleXLSXGen::fromArray( $books );
$xlsx->saveAs('books.xlsx'); // or downloadAs('books.xlsx') or $xlsx_content = (string) $xlsx 
?>
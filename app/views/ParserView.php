<?php
    class ParserView{


        public function render(){
       
            echo "<!DOCTYPE html>
            <html lang=\"en\">
            <head>
                <meta charset=\"UTF-8\">
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
                <title>Search Page</title>
            </head>
            <body>";
            echo "<div style=\"text-align: center; max-width: 500px;
            margin: auto;\">";
            echo " <h2>Result of searching</h2>";
            echo "<p id='urlImg'>".$_POST['urlImg']."</p>";
            echo "<div id='container' style=\"  overflow: auto;word-wrap: break-word; /* Перенос длинных слов на следующую строку */
            word-break: break-word;\">
          
                    <h3 id='loader'>Loading</h3>
            </div>";
            echo "<div id='stat'>
                <p id='count'></p></br>
                <p id='size'></p>
            </div>";
            echo "<div> <button onclick='history.go(-1)';>Back </button></div>";
            echo "</div>";
            echo "<script defer src=\"../travel/app/public/script.js\"></script></body>
            </html>";
         }
         
    }
    ?>
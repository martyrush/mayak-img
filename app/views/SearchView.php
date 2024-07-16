<?php
    class SearchView 
    {
        public function render()
        {
           echo "
           <!DOCTYPE html>
           <html lang=\"en\">
           <head>
               <meta charset=\"UTF-8\">
               <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
               <title>Search Page</title>
           </head>
           <body>
            <div style=\"text-align: center; max-width: 500px;
            margin: auto;\">
               <h1>Search Page</h1>
               <form method='POST' action=\"/travel/parse\">
                   <input type=\"text\" name=\"urlImg\" placeholder=\"Search...\">
                   <button type=\"submit\">Go</button>
               </form>
               </div>
           </body>
           </html>
           ";
        }
    }
?>

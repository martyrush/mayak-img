document.addEventListener('DOMContentLoaded', function () {
    const lazyImages = [].slice.call(document.querySelectorAll('img.lazy'));

    if ('IntersectionObserver' in window) {
        let lazyImageObserver = new IntersectionObserver(function (entries, observer) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    let lazyImage = entry.target;
                    lazyImage.src = lazyImage.dataset.src;
                    lazyImage.classList.remove('lazy');
                    lazyImageObserver.unobserve(lazyImage);
                }
            });
        });

        lazyImages.forEach(function (lazyImage) {
            lazyImageObserver.observe(lazyImage);
        });
    }


const loader = document.getElementById('loader');
const dataContainer = document.getElementById('container');
loader.style.display = 'block';
dataContainer.innerHTML = '';
    fetchImages();
    
    function fetchImages(){
        var urlImg = document.getElementById('urlImg').textContent; 
        fetch('/travel/api/getImages', {
                method: 'POST',
                headers: {
                'Content-Type': 'application/json;charset=utf-8'
                },
                body: JSON.stringify({url:urlImg})
                }).then(response => {
                if(response.ok){
                    return response.json();  
                }else{
                    dataContainer.innerHTML=JSON.stringify("Ошибка запроса");
                    throw new Error('Request failed!');
                }
                }, networkError => {
                console.log(networkError.message);
                dataContainer.innerHTML=JSON.stringify("Ошибка запроса");
                }).then(jsonResponse => {
                console.log(jsonResponse);
                dataContainer.innerHTML=JSON.stringify(jsonResponse);
                const count = Object.keys(jsonResponse).length;
                renderImages(jsonResponse);
                dataContainer.innerHTML+="</br><p>Number of images (count) = "+count+"</p>";
                
                getSizeImages(jsonResponse);
                });

    }
    function getSizeImages(images){
        fetch('/travel/api/getImageSize', {
            method: 'POST',
            headers: {
            'Content-Type': 'application/json;charset=utf-8'
            },
            body: JSON.stringify({img:images})
            }).then(response => {
            if(response.ok){
                return response.json();  
            }else{
                dataContainer.innerHTML=JSON.stringify("Request failed");
                throw new Error('Request failed!');
            }
            }, networkError => {
            console.log(networkError.message);
            dataContainer.innerHTML=JSON.stringify("Request failed");
            }).then(jsonResponse => {
            console.log(jsonResponse);
          
            dataContainer.innerHTML+="<p> Image size  = "+jsonResponse+"</p>";
           
            });

    }
    function renderImages(data){
        const table = document.createElement('table');
        let k=0;
        for (let i = 0; i < Math.ceil(data.length/4); i++) { 
            const tr = document.createElement('tr');
            for (let j = 0; j < 4; j++) { 
                if(k<data.length)
                    {
                        const td = document.createElement('td');
                        const im = document.createElement('img');
                        td.style.border = "1px solid black";
                        im.src = data[k];
                        im.style.maxWidth = "100px";
                        im.style.maxheight = "100px";
                      
                        im.classList.add('lazy');
                        td.appendChild(im);
                        tr.appendChild(td);
                    }
                    else{
                        break;
                    }
                k++;
            }
            table.appendChild(tr);
        }
         
            dataContainer.appendChild(table);
      
    }

});

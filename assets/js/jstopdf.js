import html2canvas from 'html2canvas';

if (document.getElementById('graphes')) {
    setTimeout(() => {
        html2canvas(document.body, {
            useCORS: true,
            preferCanvas: true,
            allowTaint: false,
            onrendered: function(canvas)
            {
                var img = canvas.toDataURL("image/png");
                var image=new Image();

                image.setAttribute("src",img);

            }
        }).then(function(canvas) {
            const imageUrl = canvas.toDataURL()

            fetch('/savepic', {
                method: "POST",
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    img: imageUrl
                })
            }).then(response => {
                if(confirm('Votre page a bien été générée, voulez vous exporter votre dossier de références maintenant ?')) {
                    window.location.href = '/dossiers'
                } else {
                    window.close()
                }
            })

        });
    }, 1000)

}

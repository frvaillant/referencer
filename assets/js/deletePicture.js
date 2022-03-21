if (document.getElementsByClassName('remove-img')) {

    const deletors = document.getElementsByClassName('remove-img')

    deletors.forEach(deletor => {
        deletor.addEventListener('click', () => {
            const study = deletor.dataset.study
            const parent = document.getElementById('pic' + deletor.dataset.parent)
            const imageNumber = (deletor.dataset.parent == 1) ? '' : 2

            if (confirm('Etes vous sur de vouloir supprimer cette image ?')) {
                fetch('/removepicture', {
                    method: "POST",
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        study: study,
                        image: imageNumber
                    }),
                }).then(response => {
                    if (200 === response.status) {
                        M.toast({html: 'Image supprimée', classes: 'maincolor'})
                        parent.remove()
                    } else {
                        M.toast({html: 'Une erreur s\'est produite', classes: 'red'})
                    }
                })
            }
        })
    })
}

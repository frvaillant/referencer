if (document.getElementById('downloader-excel')) {
    const button = document.getElementById('downloader-excel')
    const btnParent = button.parentElement
    button.addEventListener('click', () => {
        const loader = document.getElementById('loader')
        loader.classList.remove('hide')
        fetch('/savetoexcel/', {
            method:"GET",
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
        }).then(response => {
            if (200 === response.status) {
                loader.classList.add('hide')
                return response.json()
            }
        }).then(json => {
            M.toast({html:'Fichier généré', classes: 'teal'})
            const btn = document.createElement('a')
            btn.classList.add('maincolor', 'btn', 'pulse')
            btn.innerHTML = '<i class="material-icons-outlined left">grid_on</i> Télécharger'
            btn.setAttribute('download', 'dossier de références.xlsx')
            btn.setAttribute('href', json.file)
            button.remove()
            btnParent.appendChild(btn)
            setTimeout(()=> {
                btn.classList.remove('pulse')
            }, 3000)
        })
    })
}

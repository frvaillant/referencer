    const flashClosers = document.getElementsByClassName('flash-closer')

    for (let i = 0; i < flashClosers.length; i++) {
        flashClosers[i].addEventListener('click', (e) => {
            //alert(flashClosers[i].dataset.target)
            document.getElementById(flashClosers[i].dataset.target).classList.add('hide')
        })
    }

    const messages = document.getElementsByClassName('flash')

    messages.forEach(message => {
        setTimeout(() => {
            message.remove()
        }, 7000)
    })



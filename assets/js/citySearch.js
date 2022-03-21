if (document.getElementById('study_cityName')) {
    const select = document.createElement('select')
    select.setAttribute('id', 'study_citySelect');
    const selectZone = document.getElementById('cityPrecision')
    const city = document.getElementById('study_city')
    const cityName = document.getElementById('study_cityName')
    const ok = document.getElementById('cityOK')

    cityName.addEventListener('change', () => {

        fetch('/cities', {
            method: "POST",
            header: "application/json",
            body: JSON.stringify({
                cityName: cityName.value
            })
        }).then(response => {
            if (200 === response.status) {
                return response.json()
            } else {
                M.toast({html: 'aucune ville correspondant', classes: "red"})
            }
        }).then(json => {
            select.innerHTML = ''
            if (1 === json.length) {
                console.log(json)
                city.value = json[0].id
                cityName.value = json[0].name
                M.toast({html: 'Ville trouvée et assignée', classes: 'black'})
                cityName.setAttribute('disabled', true)
                ok.classList.remove('hide')
            } else {
                M.toast({html: 'Merci de préciser la ville', classes: 'black'})
                let option = document.createElement('option')
                option.value = ''
                option.innerText = 'Merci de précisez la ville'
                option.setAttribute('disabled', true)
                select.appendChild(option)

                city.value = json[0].id

                for (let key in json) {
                    let option = document.createElement('option')
                    option.value = parseInt(json[key].id)
                    option.innerText = json[key].name + ' (' + json[key].zip.slice(0, 2) + ')'
                    select.appendChild(option)
                }
                selectZone.appendChild(select)
                const selectsAppendInstances = M.FormSelect.init(document.querySelectorAll('select'));
                selectZone.classList.remove('hide')
            }
        })
    })

    select.addEventListener('change', () => {
        city.value = select.value
        const cityChoosed = select.querySelector('option[value="' + select.value + '"]')
        const [cityChoosedName, department] = cityChoosed.text.split(' (')
        cityName.value = cityChoosedName
        cityName.setAttribute('disabled', true)
        ok.classList.remove('hide')
        M.toast({html:'ville enregistrée'})
    })
}

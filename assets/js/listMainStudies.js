document.addEventListener('DOMContentLoaded', () => {

    const buttonSubmit = document.getElementById('submitter');
    const btnZone      = document.getElementById('btn-zone');
    const loader       = document.getElementById('loader');

    if (buttonSubmit) {
        buttonSubmit.addEventListener('click', () => {

            const url = document.getElementById('sort-list').value;
            let data = document.getElementById('list-input').value;

            const excludeDesc = document.getElementById('exclude-desc').checked;
            const excludeClient = document.getElementById('exclude-client').checked;
            const excludeCategory = document.getElementById('exclude-cat').checked;

            const includeStats = (document.getElementById('include-stat')) ? document.getElementById('include-stat').checked : null

            const includeEquipments = document.getElementById('include-equ').checked;

            const limitDate = (document.getElementById('ref-date').value !== '') ? document.getElementById('ref-date').value : null
            loader.classList.remove('hide');
            if (data.length === 0) {
                data = 'null'
            }
            fetch(url + '/' + data + '/' + excludeDesc + '/' + limitDate + '/' + excludeClient + '/' + excludeCategory + '/' + includeStats + '/' + includeEquipments, {
                method: 'POST'
            }).then(response => {
                loader.classList.add('hide')
                if (response.status === 200) {
                    buttonSubmit.classList.add('hide')
                    const reloadBtn = '<a class="btn red right mgl10" href="/dossiers/" id="reload">\n' +
                        '                        <i class="material-icons left">refresh</i> recommencer' +
                        '                    </a> ' +
                        '<a class="btn black right" href="/email/send/" id="sendmail">\n' +
                        '                        <i class="material-icons left">mail</i> transmettre par mail' +
                        '                    </a>'
                    const preview = '' +
                        '<object data="/pdf/references.pdf#toolbar=0" type="application/pdf" width="100%"> ' +
                        '<embed src="/pdf/references.pdf#toolbar=0" width="100%" /> ' +
                        '</embed>' +
                        '</object> '
                    document.getElementById('pdf-generate-zone').innerHTML = '<span class="pdf-success"><i class="material-icons left">check</i> Votre dossier de référence a été généré avec succès ' + reloadBtn + ' </span>' + preview

                }
            })
        })
    }


})

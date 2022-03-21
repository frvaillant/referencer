if(document.getElementById('study_category')) {
    const selector = document.getElementById('study_category')
    const subCategorySelector = document.getElementById('study_sub_categories')

    selector.addEventListener('change', () => {
        fetch('/api/sub_categories?category=' + selector.value, {
            method:"GET"
        }).then(response => {
            return response.json()
        }).then(json => {
            const subCategories = json['hydra:member']
            subCategorySelector.innerHTML = ''

            for (let key in subCategories) {
                const option     = document.createElement('option')
                option.value     = subCategories[key].id
                option.innerText = subCategories[key].name
                subCategorySelector.appendChild(option)
            }

            const selects = document.querySelectorAll('select');
            const selectsInstances = M.FormSelect.init(selects);
        })
    })


}

if (document.getElementsByClassName('fileuploader')) {

    document.getElementsByClassName('fileuploader').forEach(field => {
        field.addEventListener('change', () => {
           if (field.files[0].size > 8000000) {
               field.files[0] = null
               field.value = null
               M.toast({html:'Cette image est trop volumineuse. Choisir une image de 8Mo maximum', classes: 'red'})
           }
        })
    })

}

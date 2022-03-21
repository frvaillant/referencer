document.addEventListener('DOMContentLoaded', () => {

    const listener = document.getElementById('study_keywords');

    if (listener) {
        const receiver = document.getElementById('keywords-list');
        const field = document.getElementById('list-keywords-added');

        const arrayRemove = (arr, value) => {
            let newArr = []
            for (let i = 0; i < arr.length; i++) {
                if (arr[i].trim() != value.trim()) {
                    newArr[newArr.length] = arr[i].trim()
                }
            }
            return newArr
        }

        const initRemovers = (field) => {
            const removers = document.querySelectorAll('.remover');
            for (let i = 0; i < removers.length; i++) {
                removers[i].addEventListener('click', (e) => {
                    e.preventDefault()
                    const keyword = removers[i].parentElement.dataset.keyword.trim()
                    let keywords = field.value.split(',')
                    keywords = arrayRemove(keywords, keyword)
                    if (keywords.length === 1 && keywords[0] === '') {
                        keywords = []
                    }
                    field.value = keywords
                    removers[i].parentElement.remove()
                })
            }
        }

        const removeKeyword = (elem, field) => {
            const text = elem.innerText.slice(0, -5)
            let keywords = field.value.split(',');
            keywords = arrayRemove(keywords, text)
            field.value = keywords
        }

        listener.addEventListener('keyup', (e) => {
            if (e.key === ',') {
                const field = document.getElementById('list-keywords-added')
                let keywords = field.value.split(',');
                if (keywords.length === 1 && keywords[0] === '') {
                    keywords = []
                }
                const keyword = listener.value.slice(0, -1);
                receiver.innerHTML += '\n' +
                    '  <div class="chip" data-keyword="' + keyword + '">\n' +
                    keyword + '\n' +
                    '    <a href="#" class="remover btn-floating btn-small"><i class="material-icons">close</i></a>\n' +
                    '  </div>\n' +
                    '        ';
                keywords[keywords.length] = keyword;
                field.value = keywords;
                listener.value = '';
                initRemovers(field)
            }
        })

        initRemovers(field)
    }
})

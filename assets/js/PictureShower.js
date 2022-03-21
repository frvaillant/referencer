export class PictureShower {
    constructor(btn) {
        this.button = btn
        this.file = btn.dataset.file
        this.div = this.createBack();
        this.img = this.createPic();


        document.body.appendChild(this.div);
        this.img.addEventListener('click', () => {
            this.hide()
        })
        this.button.addEventListener('click', () => {
            this.show()
        })


    }

    createBack() {
        const div = document.createElement('div')
        div.classList.add('visioBg', 'hide')
        return div
    }

    createPic() {
        const img = document.createElement('img')
        img.setAttribute('src', this.file)
        img.classList.add('visioImg')
        this.div.appendChild(img)
        return img
    }

    show() {
        this.div.classList.remove('hide')
        this.div.classList.add('fadein')
    }

    hide() {
        this.div.classList.remove('fadein')
        this.div.classList.add('hide')
    }
}

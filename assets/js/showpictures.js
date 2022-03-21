import{PictureShower} from "./PictureShower";

if (document.getElementsByClassName('show-image')) {

    const showers = document.getElementsByClassName('show-image')

    showers.forEach(button => {
        const visionner = new PictureShower(button)
    })

}

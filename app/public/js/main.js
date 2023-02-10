// Un ficher script
// alert('test chargement')

const elements = document.getElementsByClassName('btnClick');
const liste = document.getElementById('livreList');
const container = document.getElementsByClassName('container')
const listElem = Array.from(elements);
const parcour = listElem.map(btnClick => {
    btnClick.addEventListener('click', function () {
        let url = "/filter/" + this.id;
        console.log(url);
        fetch(url, { header: 'no-core' })
            .then(response => {
                return response.json();
            }).then(data => {
                liste.innerHTML = "";
                liste.innerHTML = data.html; // On remplace le contenu de la div par le json
            }).catch(error => console.log(error));
    });
});

// error => console.log(error) function flécher
// function(error) {
//     console.log(error)
// }
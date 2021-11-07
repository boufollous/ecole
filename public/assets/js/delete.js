const buttons = document.querySelectorAll('[data-delete-button]')
if (buttons.length > 0) {
    buttons.forEach(button => {
        const [url, _token, content] = [
            button.getAttribute('data-url'),
            button.getAttribute('data-token'),
            button.innerHTML
        ];

        button.addEventListener('click', async (e) => {
            e.preventDefault();
            const confirmation = window.confirm("voulez-vous vraiment supprimer cet élément ?");
            if (confirmation) {
                try {
                    createButtonLoader(button);
                    const response = await fetch(url, {
                        method: 'DELETE',
                        body: JSON.stringify({_token})
                    })

                    // actualise la page pour voir les changement après suppression
                    response.status === 202 ? window.location.reload() : removeButtonLoader(button, content)
                    window.alert('Suppression effectuée avec succès !')
                } catch (e) {
                    console.error({e})
                    removeButtonLoader(button, content)
                    window.alert('Désolé une erreur s\'est produite !')
                }
            }
        })
    })
}

const createButtonLoader = (button, loadingText = 'chargement...') => {
    button.setAttribute('disabled', 'disabled')
    button.innerHTML = `
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        <span>${loadingText}</span>
    `
}

const removeButtonLoader = (button, text) => {
    button.removeAttribute('disabled')
    button.innerHTML = text
}


const removeFadeOut = (element, speed = 300) => {
    const seconds = speed / 1000
    element.style.transition = `opacity ${seconds}s ease`
    element.style.opacity = 0
    setTimeout(() => element.parentNode.removeChild(element), speed)
}

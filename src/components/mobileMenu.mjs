const mobileMenu = () => {
    const menu = document.querySelector('.mobileMenu')

    document.addEventListener('click', (e) => {
        if (e.target.closest('.openMenu')) {
            menu.classList.add('active')
        }
        if (e.target.closest('.closeMenu')) {
            menu.classList.remove('active')
        }

    })

}

export default mobileMenu
const tabs = () => {
    const wrapper = document.querySelector('.tabs')
    if (!wrapper) return
    
    const controls = Array.from(wrapper.querySelectorAll('.tab'))
    const contents = Array.from(wrapper.querySelectorAll('.tabContent'))

    wrapper.addEventListener('click', (e) => {
        const tab = e.target.closest('.tab')
        if (!tab) return

        const index = controls.indexOf(tab)
        if (index === -1) return

        controls.forEach(el => el.classList.remove('active'))
        contents.forEach(el => el.classList.remove('active'))

        tab.classList.add('active')
        contents[index]?.classList.add('active')
    })
}

export default tabs
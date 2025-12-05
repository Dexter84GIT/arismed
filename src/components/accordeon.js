const accordeon = () => {
    const wrap = document.querySelectorAll('.accordeon')
    wrap.forEach(acc => {
        const items = acc.querySelectorAll('.item')
        items.forEach(item => {
            item.addEventListener('click', (e) => {
                item.classList.toggle('open')
            })
        })
    })
}

export default accordeon
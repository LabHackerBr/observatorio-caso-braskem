document.addEventListener("DOMContentLoaded", function() {

    let linkList = ""
    const anchors = document.getElementById("anchors")
    const titles = document.querySelectorAll(".content > h1, .content > h2, .content > h3, .content > h4, .content > h5, .content > h6")

    const initialHash = window.location.hash

    if (anchors && titles) {
        titles.forEach((title, index) => {
            const content = title.innerText.replace(/<[^>]+>/g, '')
            const id = `${index}-section`
            title.setAttribute("id", `${id}`)
            linkList += `<li><a href="#${id}">${content}</a></li>`
        });

        anchors.innerHTML = linkList

        initAnchorSidebar(anchors, titles, initialHash)
    }

    function initAnchorSidebar(anchors, titles, initialHash) {
        const header = document.querySelector(".main-header")
        const links = Array.from(anchors.querySelectorAll("a"))
        const titleList = Array.from(titles)

        // Offset do sticky = altura do header
        const setHeaderHeight = () => {
            if (header) {
                document.documentElement.style.setProperty("--main-header-height", `${header.offsetHeight}px`)
            }
        }

        setHeaderHeight()

        if (header && "ResizeObserver" in window) {
            new ResizeObserver(setHeaderHeight).observe(header)
        }

        let activeIndex = -1

        const setActive = (index) => {
            if (index === activeIndex) {
                return
            }

            activeIndex = index

            links.forEach((link, i) => {
                link.parentElement.classList.toggle("current-section", i === index)
            })

            if (index >= 0 && titleList[index].id) {
                history.replaceState(history.state, "", `#${titleList[index].id}`)
            }

            keepActiveLinkVisible(index)
        }

        const keepActiveLinkVisible = (index) => {
            if (index < 0) {
                return
            }

            const list = anchors
            const link = links[index]
            const listTop = list.getBoundingClientRect().top
            const linkTop = link.getBoundingClientRect().top - listTop + list.scrollTop
            const linkBottom = linkTop + link.offsetHeight

            if (linkTop < list.scrollTop) {
                list.scrollTop = linkTop
            } else if (linkBottom > list.scrollTop + list.clientHeight) {
                list.scrollTop = linkBottom - list.clientHeight
            }
        }

        // Seção ativa = último título que ultrapassou o terço superior da viewport
        const updateActiveSection = () => {
            const detectionLine = window.innerHeight / 3
            let index = -1

            for (let i = 0; i < titleList.length; i++) {
                if (titleList[i].getBoundingClientRect().top <= detectionLine) {
                    index = i
                } else {
                    break
                }
            }

            setActive(index === -1 && titleList.length ? 0 : index)
        }

        if ("IntersectionObserver" in window) {
            const observer = new IntersectionObserver(updateActiveSection, {
                rootMargin: "0px 0px -66% 0px",
                threshold: 0,
            })

            titleList.forEach((title) => observer.observe(title))
        }

        // O IO pode não re-disparar após reflows (lazy-load de imagens mudando a
        // geometria da página), deixando o estado ativo desatualizado. O listener
        // de scroll (e o evento load) reconciliam com a geometria real.
        let ticking = false

        const reconcileOnScroll = () => {
            if (ticking) {
                return
            }

            ticking = true
            requestAnimationFrame(() => {
                ticking = false
                updateActiveSection()
            })
        }

        window.addEventListener("scroll", reconcileOnScroll, { passive: true })
        window.addEventListener("load", reconcileOnScroll)

        const initialIndex = titleList.findIndex((title) => `#${title.id}` === initialHash)

        if (initialIndex >= 0) {
            const title = titleList[initialIndex]
            const offset = Number.parseFloat(getComputedStyle(title).scrollMarginTop) || (header ? header.offsetHeight : 0) + 16
            const targetY = title.getBoundingClientRect().top + window.scrollY - offset

            window.scrollTo(0, Math.max(0, targetY))
            setActive(initialIndex)
        } else {
            updateActiveSection()
        }
    }
})

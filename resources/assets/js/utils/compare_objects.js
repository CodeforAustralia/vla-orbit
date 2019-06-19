//Taken from and inspired from this gist https://gist.github.com/nicbell/6081098
// and specfically from this comment https://gist.github.com/nicbell/6081098#gistcomment-2925729
Object.compare = (a, b) => {
    let s = o => Object.entries(o).sort().map(i => {
        if (i[1] instanceof Object)
            i[1] = s(i[1]);
        return i
    })
    return JSON.stringify(s(a)) === JSON.stringify(s(b))
}

export default Object;
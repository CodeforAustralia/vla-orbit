/**
 * This library was taken from https://github.com/pstephan1187/vue-datatable 
 * He made an excelent library and as we wanted to use it in IE11 we had to transpile it and do it in our end.
 */
import {
    get,
    set
} from "object-path";
var script = {
    props: {
        column: [Object, Array],
        row: [Object, Array]
    },
    computed: {
        content() {
            return this.column.getRepresentation(this.row)
        }
    }
};
const __vue_script__ = script;
var __vue_render__ = function () {
        var t = this,
            e = t.$createElement,
            s = t._self._c || e;
        return s("td", {
            style: {
                "text-align": t.column.align
            }
        }, [t.column.component ? s(t.column.component, {
            tag: "component",
            attrs: {
                row: t.row,
                column: t.column
            }
        }) : t.column.interpolate ? s("span", {
            domProps: {
                innerHTML: t._s(t.content)
            }
        }) : s("span", [t._v(t._s(t.content))])], 1)
    },
    __vue_staticRenderFns__ = [];
const __vue_inject_styles__ = void 0,
    __vue_scope_id__ = void 0,
    __vue_module_identifier__ = void 0,
    __vue_is_functional_template__ = !1;

function __vue_normalize__(t, e, s, n, i, a, r, o) {
    const l = ("function" == typeof s ? s.options : s) || {};
    return l.__file = "vue-datatable-cell.vue", l.render || (l.render = t.render, l.staticRenderFns = t.staticRenderFns, l._compiled = !0, i && (l.functional = !0)), l._scopeId = n, l
}
var VueDatatableCell = __vue_normalize__({
        render: __vue_render__,
        staticRenderFns: __vue_staticRenderFns__
    }, void 0, __vue_script__, void 0, !1, void 0, void 0, void 0),
    script$1 = {
        props: {
            model: {
                prop: "direction",
                event: "change"
            },
            column: [Object, Array],
            settings: Object,
            direction: {
                type: String,
                default: null
            }
        },
        computed: {
            canSort() {
                return this.column.sortable
            },
            is_sorted_ascending() {
                return "asc" === this.direction
            },
            is_sorted_descending() {
                return "desc" === this.direction
            },
            is_sorted() {
                return this.is_sorted_descending || this.is_sorted_ascending
            },
            classes() {
                var t = this.settings.get("table.sorting.classes"),
                    e = t.canSort;
                return this.canSort ? this.is_sorted ? (this.is_sorted_ascending && (e = e.concat(t.sortAsc)), this.is_sorted_descending && (e = e.concat(t.sortDesc)), this.joinClasses(e)) : (e = e.concat(t.sortNone), this.joinClasses(e)) : ""
            }
        },
        methods: {
            joinClasses(t) {
                return this.unique(t).join(" ")
            },
            toggleSort() {
                this.direction && null !== this.direction ? "asc" === this.direction ? this.$emit("change", "desc", this.column) : this.$emit("change", null, this.column) : this.$emit("change", "asc", this.column)
            },
            unique(t) {
                var e = {};
                return t.filter(function (t) {
                    return !e.hasOwnProperty(t) && (e[t] = !0)
                })
            }
        }
    };
const __vue_script__$1 = script$1;
var __vue_render__$1 = function () {
        var t = this,
            e = t.$createElement,
            s = t._self._c || e;
        return s("th", {
            class: t.column.headerClass,
            style: {
                "text-align": t.column.align
            }
        }, [t.column.headerComponent ? s(t.column.headerComponent, {
            tag: "component",
            attrs: {
                column: t.column
            }
        }) : s("span", [t._v(t._s(t.column.label))]), t._v(" "), t.column.sortable ? s("span", {
            class: t.classes,
            on: {
                click: t.toggleSort
            }
        }) : t._e()], 1)
    },
    __vue_staticRenderFns__$1 = [];
const __vue_inject_styles__$1 = void 0,
    __vue_scope_id__$1 = void 0,
    __vue_module_identifier__$1 = void 0,
    __vue_is_functional_template__$1 = !1;

function __vue_normalize__$1(t, e, s, n, i, a, r, o) {
    const l = ("function" == typeof s ? s.options : s) || {};
    return l.__file = "vue-datatable-header.vue", l.render || (l.render = t.render, l.staticRenderFns = t.staticRenderFns, l._compiled = !0, i && (l.functional = !0)), l._scopeId = n, l
}
var VueDatatableHeader = __vue_normalize__$1({
    render: __vue_render__$1,
    staticRenderFns: __vue_staticRenderFns__$1
}, void 0, __vue_script__$1, void 0, !1, void 0, void 0, void 0);
class Settings {
    constructor() {
        this.properties = {
            table: {
                class: "table table-hover table-striped",
                row: {
                    classes: [""]
                },
                sorting: {
                    classes: {
                        canSort: ["sort"],
                        sortNone: ["glyphicon", "glyphicon-sort"],
                        sortAsc: ["glyphicon", "glyphicon-sort-by-alphabet"],
                        sortDesc: ["glyphicon", "glyphicon-sort-by-alphabet-alt"]
                    }
                }
            },
            pager: {
                classes: {
                    pager: "pagination",
                    li: "",
                    a: "",
                    selected: "active",
                    disabled: "disabled"
                },
                icons: {
                    previous: "&lt;",
                    next: "&gt;"
                }
            }
        }
    }
    get(t) {
        return get(this.properties, t)
    }
    set(t, e) {
        return set(this.properties, t, e), this
    }
    merge(t) {
        return this.properties = this._mergeObjects(this.properties, t), this
    }
    _mergeObjects(t, e) {
        for (var s in e) null !== e[s] ? "object" != typeof e[s] ? t[s] = e[s] : t[s] = this._mergeObjects(t[s], e[s]) : t[s] = e[s];
        return t
    }
}
var script$2 = {
    props: {
        disabled: {
            type: Boolean,
            default: !1
        },
        selected: {
            type: Boolean,
            default: !1
        },
        value: {
            type: Number,
            default: null
        }
    },
    computed: {
        li_classes() {
            var t = [];
            return this.settings.get("pager.classes.li") && t.push(this.settings.get("pager.classes.li")), this.disabled && t.push(this.settings.get("pager.classes.disabled")), this.selected && t.push(this.settings.get("pager.classes.selected")), t.join(" ")
        },
        a_classes() {
            var t = [];
            return this.settings.get("pager.classes.a") && t.push(this.settings.get("pager.classes.a")), t.join(" ")
        },
        settings() {
            return this.$parent.settings
        }
    },
    methods: {
        sendClick() {
            this.disabled || this.$emit("click", this.value)
        }
    }
};
const __vue_script__$2 = script$2;
var __vue_render__$2 = function () {
        var t = this.$createElement,
            e = this._self._c || t;
        return e("li", {
            class: this.li_classes
        }, [e("a", {
            class: this.a_classes,
            attrs: {
                href: "javascript: void(0);"
            },
            on: {
                click: this.sendClick
            }
        }, [this._t("default", [this._v(this._s(this.value))])], 2)])
    },
    __vue_staticRenderFns__$2 = [];
const __vue_inject_styles__$2 = void 0,
    __vue_scope_id__$2 = void 0,
    __vue_module_identifier__$2 = void 0,
    __vue_is_functional_template__$2 = !1;

function __vue_normalize__$2(t, e, s, n, i, a, r, o) {
    const l = ("function" == typeof s ? s.options : s) || {};
    return l.__file = "vue-datatable-pager-button.vue", l.render || (l.render = t.render, l.staticRenderFns = t.staticRenderFns, l._compiled = !0, i && (l.functional = !0)), l._scopeId = n, l
}
var VueDatatablePagerButton = __vue_normalize__$2({
    render: __vue_render__$2,
    staticRenderFns: __vue_staticRenderFns__$2
}, void 0, __vue_script__$2, void 0, !1, void 0, void 0, void 0);
class Column {
    constructor(t) {
        this.setAlignment(t.align), this.label = t.label || "", this.field = t.field || null, this.representedAs = t.representedAs || null, this.component = t.component || null, this.interpolate = t.interpolate || !1, this.headerComponent = t.headerComponent || null, this.sortable = this.isSortable(t), this.filterable = this.isFilterable(t), this.headerClass = t.headerClass || ""
    }
    setAlignment(t) {
        return t && "string" == typeof t ? "center" === t.toLowerCase() ? (this.align = "center", this) : "right" === t.toLowerCase() ? (this.align = "right", this) : (this.align = "left", this) : (this.align = "left", this)
    }
    isFilterable(t) {
        return !1 !== t.filterable && (!(!t.field && !t.representedAs) && !(this.component && !this.representedAs && !this.field))
    }
    isSortable(t) {
        return !1 !== t.sortable && (!(!t.field && !t.representedAs) && !(this.component && !this.representedAs && !this.field))
    }
    getRepresentation(t) {
        return this.representedAs && "function" == typeof this.representedAs ? this.representedAs(t) : this.component && this.filterable ? this.plain_text_function(t, this) : get(t, this.field)
    }
    getValue(t) {
        return this.getRepresentation(t)
    }
    matches(t, e) {
        return -1 !== ("" + this.getRepresentation(t)).toLowerCase().indexOf(e.toLowerCase())
    }
}
var script$3 = {
    props: {
        name: {
            type: String,
            default: "default"
        },
        columns: [Object, Array],
        data: [Object, Array, String, Function],
        filterBy: {
            type: [String, Array],
            default: null
        },
        rowClasses: {
            type: [String, Array, Object, Function],
            default: null
        }
    },
    data: () => ({
        sort_by: null,
        sort_dir: null,
        total_rows: 0,
        page: 1,
        per_page: null,
        processed_rows: []
    }),
    computed: {
        rows() {
            return this.data.slice(0)
        },
        settings() {
            return this.$options.settings
        },
        handler() {
            return this.$options.handler
        },
        normalized_columns() {
            return this.columns.map(function (t) {
                return new Column(t)
            })
        },
        table_class() {
            return this.settings.get("table.class")
        }
    },
    methods: {
        getSortDirectionForColumn(t) {
            return this.sort_by !== t ? null : this.sort_dir
        },
        setSortDirectionForColumn(t, e) {
            this.sort_by = e, this.sort_dir = t
        },
        processRows() {
            if ("function" == typeof this.data) {
                let t = {
                    filter: this.filterBy,
                    sort_by: this.sort_by ? this.sort_by.field : null,
                    sort_dir: this.sort_dir,
                    page_length: this.per_page,
                    page_number: this.page
                };
                this.data(t, function (t, e) {
                    this.setRows(t), this.setTotalRowCount(e)
                }.bind(this));
                return
            }
            let t = this.handler.filterHandler(this.rows, this.filterBy, this.normalized_columns),
                e = this.handler.sortHandler(t, this.sort_by, this.sort_dir),
                s = this.handler.paginateHandler(e, this.per_page, this.page);
            this.handler.displayHandler(s, {
                filtered_data: t,
                sorted_data: e,
                paged_data: s
            }, this.setRows, this.setTotalRowCount)
        },
        setRows(t) {
            this.processed_rows = t
        },
        setTotalRowCount(t) {
            this.total_rows = t
        },
        getRowClasses(t) {
            var e = this.rowClasses;
            return null === e && (e = this.settings.get("table.row.classes")), "function" == typeof e ? e(t) : e
        }
    },
    created() {
        this.$datatables[this.name] = this, this.$root.$emit("table.ready", this.name), this.$watch(function () {
            return this.data
        }.bind(this), this.processRows, {
            deep: !0
        }), this.$watch("columns", this.processRows), this.$watch(function () {
            return this.filterBy + this.per_page + this.page + this.sort_by + this.sort_dir
        }.bind(this), this.processRows), this.processRows()
    },
    handler: null,
    settings: null
};
const __vue_script__$3 = script$3;
var __vue_render__$3 = function () {
        var t = this,
            e = t.$createElement,
            s = t._self._c || e;
        return s("table", {
            class: t.table_class
        }, [s("thead", [s("tr", t._l(t.normalized_columns, function (e, n) {
            return s("datatable-header", {
                key: n,
                attrs: {
                    column: e,
                    settings: t.settings,
                    direction: t.getSortDirectionForColumn(e)
                },
                on: {
                    change: t.setSortDirectionForColumn
                }
            })
        }))]), t._v(" "), s("tbody", [t._l(t.processed_rows, function (e, n) {
            return t._t("default", [s("tr", {
                key: n,
                class: t.getRowClasses(e)
            }, t._l(t.normalized_columns, function (t, n) {
                return s("datatable-cell", {
                    key: n,
                    attrs: {
                        column: t,
                        row: e
                    }
                })
            }))], {
                row: e,
                columns: t.normalized_columns
            })
        }), t._v(" "), 0 == t.processed_rows.length ? s("tr", [s("td", {
            attrs: {
                colspan: t.normalized_columns.length
            }
        }, [t._t("no-results")], 2)]) : t._e()], 2), t._v(" "), t.$slots.footer || t.$scopedSlots.footer ? s("tfoot", [t._t("footer", null, {
            rows: t.processed_rows
        })], 2) : t._e()])
    },
    __vue_staticRenderFns__$3 = [];
const __vue_inject_styles__$3 = void 0,
    __vue_scope_id__$3 = void 0,
    __vue_module_identifier__$3 = void 0,
    __vue_is_functional_template__$3 = !1;

function __vue_normalize__$3(t, e, s, n, i, a, r, o) {
    const l = ("function" == typeof s ? s.options : s) || {};
    return l.__file = "vue-datatable.vue", l.render || (l.render = t.render, l.staticRenderFns = t.staticRenderFns, l._compiled = !0, i && (l.functional = !0)), l._scopeId = n, l
}
var VueDatatable = __vue_normalize__$3({
        render: __vue_render__$3,
        staticRenderFns: __vue_staticRenderFns__$3
    }, void 0, __vue_script__$3, void 0, !1, void 0, void 0, void 0),
    script$4 = {
        model: {
            prop: "page",
            event: "change"
        },
        props: {
            table: {
                type: String,
                default: "default"
            },
            type: {
                type: String,
                default: "long"
            },
            perPage: {
                type: Number,
                default: 10
            },
            page: {
                type: Number,
                default: 1
            }
        },
        data: () => ({
            table_instance: null
        }),
        computed: {
            show() {
                return this.table_instance && this.total_rows > 0
            },
            total_rows() {
                return this.table_instance ? this.table_instance.total_rows : 0
            },
            pagination_class() {
                return this.settings.get("pager.classes.pager")
            },
            disabled_class() {
                return this.settings.get("pager.classes.disabled")
            },
            previous_link_classes() {
                return this.page - 1 < 1 ? this.settings.get("pager.classes.disabled") : ""
            },
            next_link_classes() {
                return this.page + 1 > this.total_pages ? this.settings.get("pager.classes.disabled") : ""
            },
            total_pages() {
                return this.total_rows > 0 ? Math.ceil(this.total_rows / this.perPage) : 0
            },
            previous_icon() {
                return this.settings.get("pager.icons.previous")
            },
            next_icon() {
                return this.settings.get("pager.icons.next")
            },
            settings() {
                return this.$options.settings
            }
        },
        methods: {
            setPageNum(t) {
                this.table_instance.page = t, this.table_instance.per_page = this.perPage, this.$emit("change", t)
            },
            getClassForPage(t) {
                return this.page == t ? this.settings.get("pager.classes.selected") : ""
            }
        },
        watch: {
            total_rows() {
                this.page > this.total_pages && this.setPageNum(this.total_pages)
            },
            perPage() {
                var t = this.page;
                t > this.total_pages && (t = this.total_pages), this.setPageNum(t)
            }
        },
        created() {
            if (this.$datatables[this.table]) return this.table_instance = this.$datatables[this.table], void(this.table_instance.per_page = this.perPage);
            this.$root.$on("table.ready", function (t) {
                t === this.table && (this.table_instance = this.$datatables[this.table], this.table_instance.per_page = this.perPage)
            }.bind(this))
        },
        settings: null
    };
const __vue_script__$4 = script$4;
var __vue_render__$4 = function () {
        var t = this,
            e = t.$createElement,
            s = t._self._c || e;
        return t.show ? s("nav", ["abbreviated" === t.type ? s("ul", {
            class: t.pagination_class
        }, [t.page - 3 >= 1 ? s("datatable-button", {
            attrs: {
                value: 1
            },
            on: {
                click: t.setPageNum
            }
        }) : t._e(), t._v(" "), t.page - 4 >= 1 ? s("datatable-button", {
            attrs: {
                disabled: ""
            }
        }, [t._v("...")]) : t._e(), t._v(" "), t.page - 2 >= 1 ? s("datatable-button", {
            attrs: {
                value: t.page - 2
            },
            on: {
                click: t.setPageNum
            }
        }) : t._e(), t._v(" "), t.page - 1 >= 1 ? s("datatable-button", {
            attrs: {
                value: t.page - 1
            },
            on: {
                click: t.setPageNum
            }
        }) : t._e(), t._v(" "), s("datatable-button", {
            attrs: {
                value: t.page,
                selected: ""
            }
        }), t._v(" "), t.page + 1 <= t.total_pages ? s("datatable-button", {
            attrs: {
                value: t.page + 1
            },
            on: {
                click: t.setPageNum
            }
        }) : t._e(), t._v(" "), t.page + 2 <= t.total_pages ? s("datatable-button", {
            attrs: {
                value: t.page + 2
            },
            on: {
                click: t.setPageNum
            }
        }) : t._e(), t._v(" "), t.page + 4 <= t.total_pages ? s("datatable-button", {
            attrs: {
                disabled: ""
            }
        }, [t._v("...")]) : t._e(), t._v(" "), t.page + 3 <= t.total_pages ? s("datatable-button", {
            attrs: {
                value: t.total_pages
            },
            on: {
                click: t.setPageNum
            }
        }) : t._e()], 1) : "long" === t.type ? s("ul", {
            class: t.pagination_class
        }, t._l(t.total_pages, function (e) {
            return s("datatable-button", {
                key: e,
                attrs: {
                    value: e,
                    selected: e === t.page
                },
                on: {
                    click: t.setPageNum
                }
            })
        })) : "short" === t.type ? s("ul", {
            class: t.pagination_class
        }, [s("datatable-button", {
            attrs: {
                disabled: t.page - 1 < 1,
                value: t.page - 1
            },
            on: {
                click: t.setPageNum
            }
        }, [s("span", {
            domProps: {
                innerHTML: t._s(t.previous_icon)
            }
        })]), t._v(" "), s("datatable-button", {
            attrs: {
                value: t.page,
                selected: ""
            }
        }), t._v(" "), s("datatable-button", {
            attrs: {
                disabled: t.page + 1 > t.total_pages,
                value: t.page + 1
            },
            on: {
                click: t.setPageNum
            }
        }, [s("span", {
            domProps: {
                innerHTML: t._s(t.next_icon)
            }
        })])], 1) : t._e()]) : t._e()
    },
    __vue_staticRenderFns__$4 = [];
const __vue_inject_styles__$4 = void 0,
    __vue_scope_id__$4 = void 0,
    __vue_module_identifier__$4 = void 0,
    __vue_is_functional_template__$4 = !1;

function __vue_normalize__$4(t, e, s, n, i, a, r, o) {
    const l = ("function" == typeof s ? s.options : s) || {};
    return l.__file = "vue-datatable-pager.vue", l.render || (l.render = t.render, l.staticRenderFns = t.staticRenderFns, l._compiled = !0, i && (l.functional = !0)), l._scopeId = n, l
}
var VueDatatablePager = __vue_normalize__$4({
    render: __vue_render__$4,
    staticRenderFns: __vue_staticRenderFns__$4
}, void 0, __vue_script__$4, void 0, !1, void 0, void 0, void 0);
class Handler {
    constructor() {
        this.filterHandler = this.handleFilter, this.sortHandler = this.handleSort, this.paginateHandler = this.handlePaginate, this.displayHandler = this.handleDisplay
    }
    handleFilter(t, e, s) {
        return e ? (Array.isArray(e) || (e = [e]), t.filter(function (t) {
            for (var n in e) {
                let a = e[n].split(/\s/),
                    r = !0;
                for (var i in a) this.rowMatches(t, a[i], s) || (r = !1);
                if (r) return !0
            }
            return !1
        }.bind(this))) : t
    }
    rowMatches(t, e, s) {
        for (var n in s)
            if (s[n].matches(t, e)) return !0;
        return !1
    }
    handleSort(t, e, s) {
        return e && null !== s ? t.sort(function (t, n) {
            var i = e.getRepresentation(t),
                a = e.getRepresentation(n);
            if (i == a) return 0;
            var r = i > a ? 1 : -1;
            return "desc" === s && (r *= -1), r
        }) : t
    }
    handlePaginate(t, e, s) {
        if (!e) return t;
        s < 1 && (s = 1);
        let n = (s - 1) * e,
            i = s * e;
        return t.slice(n, i)
    }
    handleDisplay(t, e, s, n) {
        s(t), n(e.filtered_data.length)
    }
}
class TableType {
    constructor(t) {
        this.id = t, this.handler = new Handler, this.settings = new Settings
    }
    getId() {
        return this.id
    }
    setFilterHandler(t) {
        return this.handler.filterHandler = t, this
    }
    setSortHandler(t) {
        return this.handler.sortHandler = t, this
    }
    setPaginateHandler(t) {
        return this.handler.paginateHandler = t, this
    }
    setDisplayHandler(t) {
        return this.handler.displayHandler = t, this
    }
    setting(t, e) {
        return void 0 === e ? this.settings.get(t) : (this.settings.set(t, e), this)
    }
    mergeSettings(t) {
        return this.settings.merge(t), this
    }
    getTableDefinition() {
        let t = this.clone(VueDatatable);
        return t.handler = this.handler, t.settings = this.settings, t.name = this.id, t
    }
    getPagerDefinition() {
        let t = this.clone(VueDatatablePager);
        return t.settings = this.settings, t.name = this.id, t
    }
    clone(t) {
        var e;
        if (null === t || "object" != typeof t) return t;
        if (t instanceof Array) {
            e = [];
            for (var s = 0; s < t.length; s++) e[s] = this.clone(t[s]);
            return e
        }
        if (t instanceof Object) {
            for (var n in e = {}, t) t.hasOwnProperty(n) && (e[n] = this.clone(t[n]));
            return e
        }
        throw new Error("Unable to copy obj! Its type isn't supported.")
    }
}
class DatatableFactory {
    constructor() {
        this.table_types = [], this.use_default_type = !0, this.default_table_settings = new Settings
    }
    useDefaultType(t) {
        return this.use_default_type = !!t, this
    }
    registerTableType(t, e) {
        let s = new TableType(t);
        return this.table_types.push(s), e && "function" == typeof e && e(s), this
    }
    install(t) {
        for (var e in t.prototype.$datatables = {}, t.component("datatable-cell", VueDatatableCell), t.component("datatable-header", VueDatatableHeader), t.component("datatable-button", VueDatatablePagerButton), this.use_default_type && this.registerTableType("datatable", function (t) {
                t.mergeSettings(this.default_table_settings.properties)
            }.bind(this)), this.table_types) this.installTableType(this.table_types[e].getId(), this.table_types[e], t)
    }
    installTableType(t, e, s) {
        s.component(t, e.getTableDefinition()), s.component(t + "-pager", e.getPagerDefinition())
    }
}
var index = new DatatableFactory;
export default index;

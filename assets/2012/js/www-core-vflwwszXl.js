(function() {
    function h(a) {
        throw a;
    }
    var i = void 0,
        j = !0,
        l = null,
        m = !1,
        n, p = this,
        q = function(a) {
            for (var a = a.split("."), b = p, c; c = a.shift();)
                if (b[c] != l) b = b[c];
                else return l;
            return b
        },
        aa = function(a) {
            a.getInstance = function() {
                return a.ai || (a.ai = new a)
            }
        },
        ba = function(a) {
            var b = typeof a;
            if ("object" == b)
                if (a) {
                    if (a instanceof Array) return "array";
                    if (a instanceof Object) return b;
                    var c = Object.prototype.toString.call(a);
                    if ("[object Window]" == c) return "object";
                    if ("[object Array]" == c || "number" == typeof a.length && "undefined" != typeof a.splice && "undefined" != typeof a.propertyIsEnumerable &&
                        !a.propertyIsEnumerable("splice")) return "array";
                    if ("[object Function]" == c || "undefined" != typeof a.call && "undefined" != typeof a.propertyIsEnumerable && !a.propertyIsEnumerable("call")) return "function"
                } else return "null";
            else if ("function" == b && "undefined" == typeof a.call) return "object";
            return b
        },
        ca = function(a) {
            return a !== i
        },
        da = function(a) {
            return "array" == ba(a)
        },
        ea = function(a) {
            var b = ba(a);
            return "array" == b || "object" == b && "number" == typeof a.length
        },
        ga = function(a) {
            return "string" == typeof a
        },
        ha = function(a) {
            a = ba(a);
            return "object" == a || "array" == a || "function" == a
        },
        ia = "closure_uid_" + Math.floor(2147483648 * Math.random()).toString(36),
        ja = 0,
        la = function(a, b, c) {
            return a.call.apply(a.bind, arguments)
        },
        ma = function(a, b, c) {
            a || h(Error());
            if (2 < arguments.length) {
                var d = Array.prototype.slice.call(arguments, 2);
                return function() {
                    var c = Array.prototype.slice.call(arguments);
                    Array.prototype.unshift.apply(c, d);
                    return a.apply(b, c)
                }
            }
            return function() {
                return a.apply(b, arguments)
            }
        },
        r = function(a, b, c) {
            r = Function.prototype.bind && -1 != Function.prototype.bind.toString().indexOf("native code") ?
                la : ma;
            return r.apply(l, arguments)
        },
        na = function(a, b) {
            var c = Array.prototype.slice.call(arguments, 1);
            return function() {
                var b = Array.prototype.slice.call(arguments);
                b.unshift.apply(b, c);
                return a.apply(this, b)
            }
        },
        oa = Date.now || function() {
            return +new Date
        },
        s = function(a, b) {
            var c = a.split("."),
                d = p;
            !(c[0] in d) && d.execScript && d.execScript("var " + c[0]);
            for (var e; c.length && (e = c.shift());) !c.length && ca(b) ? d[e] = b : d = d[e] ? d[e] : d[e] = {}
        },
        t = function(a, b) {
            function c() {}
            c.prototype = b.prototype;
            a.Be = b.prototype;
            a.prototype =
                new c
        };
    Function.prototype.bind = Function.prototype.bind || function(a, b) {
        if (1 < arguments.length) {
            var c = Array.prototype.slice.call(arguments, 1);
            c.unshift(this, a);
            return r.apply(l, c)
        }
        return r(this, a)
    };
    var pa = function(a) {
        this.stack = Error().stack || "";
        a && (this.message = "" + a)
    };
    t(pa, Error);
    pa.prototype.name = "CustomError";
    var qa = function(a, b) {
            for (var c = 1; c < arguments.length; c++) var d = ("" + arguments[c]).replace(/\$/g, "$$$$"),
                a = a.replace(/\%s/, d);
            return a
        },
        ra = function(a) {
            return a.replace(/^[\s\xa0]+|[\s\xa0]+$/g, "")
        },
        sa = /^[a-zA-Z0-9\-_.!~*'()]*$/,
        ta = function(a) {
            a = "" + a;
            return !sa.test(a) ? encodeURIComponent(a) : a
        },
        za = function(a) {
            if (!ua.test(a)) return a; - 1 != a.indexOf("&") && (a = a.replace(va, "&amp;")); - 1 != a.indexOf("<") && (a = a.replace(wa, "&lt;")); - 1 != a.indexOf(">") && (a = a.replace(xa, "&gt;")); - 1 != a.indexOf('"') && (a = a.replace(ya,
                "&quot;"));
            return a
        },
        va = /&/g,
        wa = /</g,
        xa = />/g,
        ya = /\"/g,
        ua = /[&<>\"]/,
        Ca = function(a) {
            return -1 != a.indexOf("&") ? "document" in p ? Aa(a) : Ba(a) : a
        },
        Aa = function(a) {
            var b = {
                    "&amp;": "&",
                    "&lt;": "<",
                    "&gt;": ">",
                    "&quot;": '"'
                },
                c = document.createElement("div");
            return a.replace(Da, function(a, e) {
                var f = b[a];
                if (f) return f;
                if ("#" == e.charAt(0)) {
                    var g = Number("0" + e.substr(1));
                    isNaN(g) || (f = String.fromCharCode(g))
                }
                f || (c.innerHTML = a + " ", f = c.firstChild.nodeValue.slice(0, -1));
                return b[a] = f
            })
        },
        Ba = function(a) {
            return a.replace(/&([^;]+);/g,
                function(a, c) {
                    switch (c) {
                        case "amp":
                            return "&";
                        case "lt":
                            return "<";
                        case "gt":
                            return ">";
                        case "quot":
                            return '"';
                        default:
                            if ("#" == c.charAt(0)) {
                                var d = Number("0" + c.substr(1));
                                if (!isNaN(d)) return String.fromCharCode(d)
                            }
                            return a
                    }
                })
        },
        Da = /&([^;\s<&]+);?/g,
        Ea = function(a, b) {
            a.length > b && (a = a.substring(0, b - 3) + "...");
            return a
        },
        Fa = {
            "\x00": "\\0",
            "\u0008": "\\b",
            "\u000c": "\\f",
            "\n": "\\n",
            "\r": "\\r",
            "\t": "\\t",
            "\x0B": "\\x0B",
            '"': '\\"',
            "\\": "\\\\"
        },
        Ga = {
            "'": "\\'"
        },
        Ha = function(a) {
            for (var b = [], c = 0; c < a.length; c++) {
                var d = b,
                    e =
                    c,
                    f;
                f = a.charAt(c);
                if (f in Ga) f = Ga[f];
                else if (f in Fa) f = Ga[f] = Fa[f];
                else {
                    var g = f,
                        k = f.charCodeAt(0);
                    if (31 < k && 127 > k) g = f;
                    else {
                        if (256 > k) {
                            if (g = "\\x", 16 > k || 256 < k) g += "0"
                        } else g = "\\u", 4096 > k && (g += "0");
                        g += k.toString(16).toUpperCase()
                    }
                    f = Ga[f] = g
                }
                d[e] = f
            }
            return b.join("")
        },
        Ia = function(a, b) {
            for (var c = 0, d = ra("" + a).split("."), e = ra("" + b).split("."), f = Math.max(d.length, e.length), g = 0; 0 == c && g < f; g++) {
                var k = d[g] || "",
                    o = e[g] || "",
                    v = RegExp("(\\d*)(\\D*)", "g"),
                    C = RegExp("(\\d*)(\\D*)", "g");
                do {
                    var E = v.exec(k) || ["", "", ""],
                        J = C.exec(o) || ["", "", ""];
                    if (0 == E[0].length && 0 == J[0].length) break;
                    c = ((0 == E[1].length ? 0 : parseInt(E[1], 10)) < (0 == J[1].length ? 0 : parseInt(J[1], 10)) ? -1 : (0 == E[1].length ? 0 : parseInt(E[1], 10)) > (0 == J[1].length ? 0 : parseInt(J[1], 10)) ? 1 : 0) || ((0 == E[2].length) < (0 == J[2].length) ? -1 : (0 == E[2].length) > (0 == J[2].length) ? 1 : 0) || (E[2] < J[2] ? -1 : E[2] > J[2] ? 1 : 0)
                } while (0 == c)
            }
            return c
        },
        Ja = function(a) {
            for (var b = 0, c = 0; c < a.length; ++c) b = 31 * b + a.charCodeAt(c), b %= 4294967296;
            return b
        },
        Ka = {},
        La = function(a) {
            return Ka[a] || (Ka[a] = ("" + a).replace(/\-([a-z])/g,
                function(a, c) {
                    return c.toUpperCase()
                }))
        };
    var Ma = function(a, b) {
        b.unshift(a);
        pa.call(this, qa.apply(l, b));
        b.shift()
    };
    t(Ma, pa);
    Ma.prototype.name = "AssertionError";
    var Na = function(a, b, c, d) {
            var e = "Assertion failed";
            if (c) var e = e + (": " + c),
                f = d;
            else a && (e += ": " + a, f = b);
            h(new Ma("" + e, f || []))
        },
        Oa = function(a, b, c) {
            !a && Na("", l, b, Array.prototype.slice.call(arguments, 2))
        },
        Pa = function(a, b) {
            h(new Ma("Failure" + (a ? ": " + a : ""), Array.prototype.slice.call(arguments, 1)))
        },
        Qa = function(a, b, c) {
            !ga(a) && Na("Expected string but got %s: %s.", [ba(a), a], b, Array.prototype.slice.call(arguments, 2));
            return a
        };
    var Ra = Array.prototype,
        Sa = Ra.indexOf ? function(a, b, c) {
            Oa(a.length != l);
            return Ra.indexOf.call(a, b, c)
        } : function(a, b, c) {
            c = c == l ? 0 : 0 > c ? Math.max(0, a.length + c) : c;
            if (ga(a)) return !ga(b) || 1 != b.length ? -1 : a.indexOf(b, c);
            for (; c < a.length; c++)
                if (c in a && a[c] === b) return c;
            return -1
        },
        u = Ra.forEach ? function(a, b, c) {
            Oa(a.length != l);
            Ra.forEach.call(a, b, c)
        } : function(a, b, c) {
            for (var d = a.length, e = ga(a) ? a.split("") : a, f = 0; f < d; f++) f in e && b.call(c, e[f], f, a)
        },
        Ta = Ra.filter ? function(a, b, c) {
            Oa(a.length != l);
            return Ra.filter.call(a,
                b, c)
        } : function(a, b, c) {
            for (var d = a.length, e = [], f = 0, g = ga(a) ? a.split("") : a, k = 0; k < d; k++)
                if (k in g) {
                    var o = g[k];
                    b.call(c, o, k, a) && (e[f++] = o)
                } return e
        },
        Ua = Ra.map ? function(a, b, c) {
            Oa(a.length != l);
            return Ra.map.call(a, b, c)
        } : function(a, b, c) {
            for (var d = a.length, e = Array(d), f = ga(a) ? a.split("") : a, g = 0; g < d; g++) g in f && (e[g] = b.call(c, f[g], g, a));
            return e
        },
        Va = Ra.some ? function(a, b, c) {
            Oa(a.length != l);
            return Ra.some.call(a, b, c)
        } : function(a, b, c) {
            for (var d = a.length, e = ga(a) ? a.split("") : a, f = 0; f < d; f++)
                if (f in e && b.call(c, e[f],
                        f, a)) return j;
            return m
        },
        Ya = function(a, b) {
            var c = Wa(a, b, i);
            return 0 > c ? l : ga(a) ? a.charAt(c) : a[c]
        },
        Wa = function(a, b, c) {
            for (var d = a.length, e = ga(a) ? a.split("") : a, f = 0; f < d; f++)
                if (f in e && b.call(c, e[f], f, a)) return f;
            return -1
        },
        Za = function(a, b) {
            for (var c = ga(a) ? a.split("") : a, d = a.length - 1; 0 <= d; d--)
                if (d in c && b.call(i, c[d], d, a)) return d;
            return -1
        },
        $a = function(a, b) {
            return 0 <= Sa(a, b)
        },
        ab = function(a) {
            if (!da(a))
                for (var b = a.length - 1; 0 <= b; b--) delete a[b];
            a.length = 0
        },
        bb = function(a, b) {
            Oa(a.length != l);
            Ra.splice.call(a, b, 1)
        },
        cb = function(a) {
            return Ra.concat.apply(Ra, arguments)
        },
        db = function(a) {
            if (da(a)) return cb(a);
            for (var b = [], c = 0, d = a.length; c < d; c++) b[c] = a[c];
            return b
        },
        eb = function(a) {
            return da(a) ? cb(a) : db(a)
        },
        fb = function(a, b) {
            for (var c = 1; c < arguments.length; c++) {
                var d = arguments[c],
                    e;
                if (da(d) || (e = ea(d)) && d.hasOwnProperty("callee")) a.push.apply(a, d);
                else if (e)
                    for (var f = a.length, g = d.length, k = 0; k < g; k++) a[f + k] = d[k];
                else a.push(d)
            }
        },
        hb = function(a, b, c, d) {
            Oa(a.length != l);
            Ra.splice.apply(a, gb(arguments, 1))
        },
        gb = function(a, b, c) {
            Oa(a.length !=
                l);
            return 2 >= arguments.length ? Ra.slice.call(a, b) : Ra.slice.call(a, b, c)
        },
        jb = function(a, b) {
            Oa(a.length != l);
            Ra.sort.call(a, b || ib)
        },
        kb = function(a) {
            var b = ib;
            jb(a, function(a, d) {
                return b(a.key, d.key)
            })
        },
        ib = function(a, b) {
            return a > b ? 1 : a < b ? -1 : 0
        };
    var lb, mb = function(a) {
            return (a = a.className) && "function" == typeof a.split ? a.split(/\s+/) : []
        },
        w = function(a, b) {
            var c = mb(a),
                d = gb(arguments, 1),
                d = nb(c, d);
            a.className = c.join(" ");
            return d
        },
        x = function(a, b) {
            var c = mb(a),
                d = gb(arguments, 1),
                d = ob(c, d);
            a.className = c.join(" ");
            return d
        },
        nb = function(a, b) {
            for (var c = 0, d = 0; d < b.length; d++) $a(a, b[d]) || (a.push(b[d]), c++);
            return c == b.length
        },
        ob = function(a, b) {
            for (var c = 0, d = 0; d < a.length; d++) $a(b, a[d]) && (hb(a, d--, 1), c++);
            return c == b.length
        },
        y = function(a, b, c) {
            var d = mb(a);
            ga(b) ?
                (b = Sa(d, b), 0 <= b && bb(d, b)) : da(b) && ob(d, b);
            ga(c) && !$a(d, c) ? d.push(c) : da(c) && nb(d, c);
            a.className = d.join(" ")
        },
        z = function(a, b) {
            return $a(mb(a), b)
        },
        A = function(a, b, c) {
            c ? w(a, b) : x(a, b)
        },
        pb = function(a, b) {
            var c = !z(a, b);
            A(a, b, c);
            return c
        };
    var qb = function(a, b) {
        this.x = ca(a) ? a : 0;
        this.y = ca(b) ? b : 0
    };
    qb.prototype.Ha = function() {
        return new qb(this.x, this.y)
    };
    qb.prototype.toString = function() {
        return "(" + this.x + ", " + this.y + ")"
    };
    var rb = function(a, b) {
        return new qb(a.x - b.x, a.y - b.y)
    };
    var sb = function(a, b) {
        this.width = a;
        this.height = b
    };
    n = sb.prototype;
    n.Ha = function() {
        return new sb(this.width, this.height)
    };
    n.toString = function() {
        return "(" + this.width + " x " + this.height + ")"
    };
    n.ceil = function() {
        this.width = Math.ceil(this.width);
        this.height = Math.ceil(this.height);
        return this
    };
    n.floor = function() {
        this.width = Math.floor(this.width);
        this.height = Math.floor(this.height);
        return this
    };
    n.round = function() {
        this.width = Math.round(this.width);
        this.height = Math.round(this.height);
        return this
    };
    n.scale = function(a) {
        this.width *= a;
        this.height *= a;
        return this
    };
    var tb = function(a, b) {
            for (var c in a) b.call(i, a[c], c, a)
        },
        ub = function(a) {
            for (var b in a) return a[b]
        },
        vb = function(a) {
            var b = [],
                c = 0,
                d;
            for (d in a) b[c++] = d;
            return b
        },
        wb = function(a, b, c) {
            for (var d in a)
                if (b.call(c, a[d], d, a)) return d
        },
        xb = function(a, b) {
            var c = wb(a, b, i);
            return c && a[c]
        },
        yb = function(a) {
            var b = {},
                c;
            for (c in a) b[c] = a[c];
            return b
        },
        zb = "constructor,hasOwnProperty,isPrototypeOf,propertyIsEnumerable,toLocaleString,toString,valueOf".split(","),
        Ab = function(a, b) {
            for (var c, d, e = 1; e < arguments.length; e++) {
                d = arguments[e];
                for (c in d) a[c] = d[c];
                for (var f = 0; f < zb.length; f++) c = zb[f], Object.prototype.hasOwnProperty.call(d, c) && (a[c] = d[c])
            }
        };
    var Bb, Cb, Db, Eb, Fb, Gb, Hb, Ib = function() {
            return p.navigator ? p.navigator.userAgent : l
        },
        Jb = function() {
            return p.navigator
        };
    Fb = Eb = Db = Cb = Bb = m;
    var Kb;
    if (Kb = Ib()) {
        var Lb = Jb();
        Bb = 0 == Kb.indexOf("Opera");
        Cb = !Bb && -1 != Kb.indexOf("MSIE");
        Eb = (Db = !Bb && -1 != Kb.indexOf("WebKit")) && -1 != Kb.indexOf("Mobile");
        Fb = !Bb && !Db && "Gecko" == Lb.product
    }
    var Mb = Bb,
        B = Cb,
        Nb = Fb,
        Ob = Db,
        Pb = Eb,
        Qb = Jb(),
        Rb = Qb && Qb.platform || "";
    Gb = -1 != Rb.indexOf("Mac");
    Hb = -1 != Rb.indexOf("Win");
    var Sb = !!Jb() && -1 != (Jb().appVersion || "").indexOf("X11"),
        Tb;
    a: {
        var Ub = "",
            Vb;
        if (Mb && p.opera) var Wb = p.opera.version,
            Ub = "function" == typeof Wb ? Wb() : Wb;
        else if (Nb ? Vb = /rv\:([^\);]+)(\)|;)/ : B ? Vb = /MSIE\s+([^\);]+)(\)|;)/ : Ob && (Vb = /WebKit\/(\S+)/), Vb) var Xb = Vb.exec(Ib()),
            Ub = Xb ? Xb[1] : "";
        if (B) {
            var Yb, Zb = p.document;
            Yb = Zb ? Zb.documentMode : i;
            if (Yb > parseFloat(Ub)) {
                Tb = "" + Yb;
                break a
            }
        }
        Tb = Ub
    }
    var $b = Tb,
        ac = {},
        D = function(a) {
            return ac[a] || (ac[a] = 0 <= Ia($b, a))
        },
        bc = {},
        cc = function(a) {
            return bc[a] || (bc[a] = B && document.documentMode && document.documentMode >= a)
        };
    var dc = !B || cc(9);
    !Nb && !B || B && cc(9) || Nb && D("1.9.1");
    var ec = B && !D("9");
    var hc = function(a) {
            return a ? new fc(gc(a)) : lb || (lb = new fc)
        },
        F = function(a) {
            return ga(a) ? document.getElementById(a) : a
        },
        jc = function(a, b) {
            var c = b || document;
            return ic(c) ? c.querySelectorAll("." + a) : c.getElementsByClassName ? c.getElementsByClassName(a) : H("*", a, b)
        },
        I = function(a, b) {
            var c = b || document,
                d = l;
            return (d = ic(c) ? c.querySelector("." + a) : jc(a, b)[0]) || l
        },
        ic = function(a) {
            return a.querySelectorAll && a.querySelector && (!Ob || kc(document) || D("528"))
        },
        H = function(a, b, c) {
            c = c || document;
            a = a && "*" != a ? a.toUpperCase() : "";
            if (ic(c) &&
                (a || b)) return c.querySelectorAll(a + (b ? "." + b : ""));
            if (b && c.getElementsByClassName) {
                c = c.getElementsByClassName(b);
                if (a) {
                    for (var d = {}, e = 0, f = 0, g; g = c[f]; f++) a == g.nodeName && (d[e++] = g);
                    d.length = e;
                    return d
                }
                return c
            }
            c = c.getElementsByTagName(a || "*");
            if (b) {
                d = {};
                for (f = e = 0; g = c[f]; f++) a = g.className, "function" == typeof a.split && $a(a.split(/\s+/), b) && (d[e++] = g);
                d.length = e;
                return d
            }
            return c
        },
        mc = function(a, b) {
            tb(b, function(b, d) {
                "style" == d ? a.style.cssText = b : "class" == d ? a.className = b : "for" == d ? a.htmlFor = b : d in lc ? a.setAttribute(lc[d],
                    b) : 0 == d.lastIndexOf("aria-", 0) ? a.setAttribute(d, b) : a[d] = b
            })
        },
        lc = {
            cellpadding: "cellPadding",
            cellspacing: "cellSpacing",
            colspan: "colSpan",
            rowspan: "rowSpan",
            valign: "vAlign",
            height: "height",
            width: "width",
            usemap: "useMap",
            frameborder: "frameBorder",
            maxlength: "maxLength",
            type: "type"
        },
        nc = function(a) {
            var b = a.document;
            if (Ob && !D("500") && !Pb) {
                "undefined" == typeof a.innerHeight && (a = window);
                var b = a.innerHeight,
                    c = a.document.documentElement.scrollHeight;
                a == a.top && c < b && (b -= 15);
                return new sb(a.innerWidth, b)
            }
            a = kc(b) ? b.documentElement :
                b.body;
            return new sb(a.clientWidth, a.clientHeight)
        },
        oc = function() {
            var a = window,
                b = a.document,
                c = 0;
            if (b) {
                var a = nc(a).height,
                    c = b.body,
                    d = b.documentElement;
                if (kc(b) && d.scrollHeight) c = d.scrollHeight != a ? d.scrollHeight : d.offsetHeight;
                else {
                    var b = d.scrollHeight,
                        e = d.offsetHeight;
                    d.clientHeight != e && (b = c.scrollHeight, e = c.offsetHeight);
                    c = b > a ? b > e ? b : e : b < e ? b : e
                }
            }
            return c
        },
        pc = function(a) {
            var b = !Ob && kc(a) ? a.documentElement : a.body,
                a = a.parentWindow || a.defaultView;
            return new qb(a.pageXOffset || b.scrollLeft, a.pageYOffset ||
                b.scrollTop)
        },
        rc = function(a, b, c) {
            return qc(document, arguments)
        },
        qc = function(a, b) {
            var c = b[0],
                d = b[1];
            if (!dc && d && (d.name || d.type)) {
                c = ["<", c];
                d.name && c.push(' name="', za(d.name), '"');
                if (d.type) {
                    c.push(' type="', za(d.type), '"');
                    var e = {};
                    Ab(e, d);
                    d = e;
                    delete d.type
                }
                c.push(">");
                c = c.join("")
            }
            c = a.createElement(c);
            d && (ga(d) ? c.className = d : da(d) ? w.apply(l, [c].concat(d)) : mc(c, d));
            2 < b.length && sc(a, c, b, 2);
            return c
        },
        sc = function(a, b, c, d) {
            function e(c) {
                c && b.appendChild(ga(c) ? a.createTextNode(c) : c)
            }
            for (; d < c.length; d++) {
                var f =
                    c[d];
                ea(f) && !(ha(f) && 0 < f.nodeType) ? u(tc(f) ? db(f) : f, e) : e(f)
            }
        },
        uc = function(a) {
            return document.createTextNode(a)
        },
        vc = function(a) {
            var b = document,
                c = b.createElement("div");
            B ? (c.innerHTML = "<br>" + a, c.removeChild(c.firstChild)) : c.innerHTML = a;
            if (1 == c.childNodes.length) return c.removeChild(c.firstChild);
            for (a = b.createDocumentFragment(); c.firstChild;) a.appendChild(c.firstChild);
            return a
        },
        kc = function(a) {
            return "CSS1Compat" == a.compatMode
        },
        wc = function(a, b) {
            sc(gc(a), a, arguments, 1)
        },
        xc = function(a) {
            for (var b; b = a.firstChild;) a.removeChild(b)
        },
        yc = function(a) {
            a && a.parentNode && a.parentNode.removeChild(a)
        },
        Bc = function(a) {
            return a.firstElementChild != i ? a.firstElementChild : zc(a.firstChild)
        },
        zc = function(a) {
            for (; a && 1 != a.nodeType;) a = a.nextSibling;
            return a
        },
        Cc = function(a, b) {
            if (a.contains && 1 == b.nodeType) return a == b || a.contains(b);
            if ("undefined" != typeof a.compareDocumentPosition) return a == b || Boolean(a.compareDocumentPosition(b) & 16);
            for (; b && a != b;) b = b.parentNode;
            return b == a
        },
        gc = function(a) {
            return 9 == a.nodeType ? a : a.ownerDocument || a.document
        },
        Dc = function(a,
            b) {
            if ("textContent" in a) a.textContent = b;
            else if (a.firstChild && 3 == a.firstChild.nodeType) {
                for (; a.lastChild != a.firstChild;) a.removeChild(a.lastChild);
                a.firstChild.data = b
            } else xc(a), a.appendChild(gc(a).createTextNode(b))
        },
        Fc = function(a, b) {
            var c = [];
            return Ec(a, b, c, j) ? c[0] : i
        },
        Ec = function(a, b, c, d) {
            if (a != l)
                for (a = a.firstChild; a;) {
                    if (b(a) && (c.push(a), d) || Ec(a, b, c, d)) return j;
                    a = a.nextSibling
                }
            return m
        },
        Gc = {
            SCRIPT: 1,
            STYLE: 1,
            HEAD: 1,
            IFRAME: 1,
            OBJECT: 1
        },
        Hc = {
            IMG: " ",
            BR: "\n"
        },
        Jc = function(a) {
            if (ec && "innerText" in a) a =
                a.innerText.replace(/(\r\n|\r|\n)/g, "\n");
            else {
                var b = [];
                Ic(a, b, j);
                a = b.join("")
            }
            a = a.replace(/ \xAD /g, " ").replace(/\xAD/g, "");
            a = a.replace(/\u200B/g, "");
            ec || (a = a.replace(/ +/g, " "));
            " " != a && (a = a.replace(/^\s*/, ""));
            return a
        },
        Ic = function(a, b, c) {
            if (!(a.nodeName in Gc))
                if (3 == a.nodeType) c ? b.push(("" + a.nodeValue).replace(/(\r\n|\r|\n)/g, "")) : b.push(a.nodeValue);
                else if (a.nodeName in Hc) b.push(Hc[a.nodeName]);
            else
                for (a = a.firstChild; a;) Ic(a, b, c), a = a.nextSibling
        },
        tc = function(a) {
            if (a && "number" == typeof a.length) {
                if (ha(a)) return "function" ==
                    typeof a.item || "string" == typeof a.item;
                if ("function" == ba(a)) return "function" == typeof a.item
            }
            return m
        },
        K = function(a, b, c) {
            var d = b ? b.toUpperCase() : l;
            return Kc(a, function(a) {
                return (!d || a.nodeName == d) && (!c || z(a, c))
            }, j)
        },
        Kc = function(a, b, c, d) {
            c || (a = a.parentNode);
            for (var c = d == l, e = 0; a && (c || e <= d);) {
                if (b(a)) return a;
                a = a.parentNode;
                e++
            }
            return l
        },
        fc = function(a) {
            this.o = a || p.document || document
        };
    fc.prototype.Zh = function(a, b, c) {
        return qc(this.o, arguments)
    };
    fc.prototype.createElement = function(a) {
        return this.o.createElement(a)
    };
    fc.prototype.createTextNode = function(a) {
        return this.o.createTextNode(a)
    };
    var Lc = function(a) {
            return kc(a.o)
        },
        Mc = function(a) {
            return pc(a.o)
        };
    fc.prototype.appendChild = function(a, b) {
        a.appendChild(b)
    };
    fc.prototype.append = wc;
    fc.prototype.contains = Cc;
    var Nc = "StopIteration" in p ? p.StopIteration : Error("StopIteration"),
        Oc = function() {};
    Oc.prototype.next = function() {
        h(Nc)
    };
    Oc.prototype.$a = function() {
        return this
    };
    var Pc = function(a) {
            if (a instanceof Oc) return a;
            if ("function" == typeof a.$a) return a.$a(m);
            if (ea(a)) {
                var b = 0,
                    c = new Oc;
                c.next = function() {
                    for (;;) {
                        b >= a.length && h(Nc);
                        if (b in a) return a[b++];
                        b++
                    }
                };
                return c
            }
            h(Error("Not implemented"))
        },
        Qc = function(a, b) {
            if (ea(a)) try {
                u(a, b, i)
            } catch (c) {
                c !== Nc && h(c)
            } else {
                a = Pc(a);
                try {
                    for (;;) b.call(i, a.next(), i, a)
                } catch (d) {
                    d !== Nc && h(d)
                }
            }
        },
        Rc = function(a) {
            if (ea(a)) return eb(a);
            var a = Pc(a),
                b = [];
            Qc(a, function(a) {
                b.push(a)
            });
            return b
        };
    var Sc = function(a, b) {
        this.O = {};
        this.B = [];
        var c = arguments.length;
        if (1 < c) {
            c % 2 && h(Error("Uneven number of arguments"));
            for (var d = 0; d < c; d += 2) this.set(arguments[d], arguments[d + 1])
        } else if (a) {
            if (a instanceof Sc) c = a.De(), d = a.Ee();
            else {
                var c = vb(a),
                    e = [],
                    f = 0;
                for (d in a) e[f++] = a[d];
                d = e
            }
            for (e = 0; e < c.length; e++) this.set(c[e], d[e])
        }
    };
    n = Sc.prototype;
    n.Ea = 0;
    n.xb = 0;
    n.G = function() {
        return this.Ea
    };
    n.Ee = function() {
        Tc(this);
        for (var a = [], b = 0; b < this.B.length; b++) a.push(this.O[this.B[b]]);
        return a
    };
    n.De = function() {
        Tc(this);
        return this.B.concat()
    };
    n.bf = function(a) {
        return Uc(this.O, a)
    };
    n.clear = function() {
        this.O = {};
        this.xb = this.Ea = this.B.length = 0
    };
    n.remove = function(a) {
        return Uc(this.O, a) ? (delete this.O[a], this.Ea--, this.xb++, this.B.length > 2 * this.Ea && Tc(this), j) : m
    };
    var Tc = function(a) {
        if (a.Ea != a.B.length) {
            for (var b = 0, c = 0; b < a.B.length;) {
                var d = a.B[b];
                Uc(a.O, d) && (a.B[c++] = d);
                b++
            }
            a.B.length = c
        }
        if (a.Ea != a.B.length) {
            for (var e = {}, c = b = 0; b < a.B.length;) d = a.B[b], Uc(e, d) || (a.B[c++] = d, e[d] = 1), b++;
            a.B.length = c
        }
    };
    Sc.prototype.get = function(a, b) {
        return Uc(this.O, a) ? this.O[a] : b
    };
    Sc.prototype.set = function(a, b) {
        Uc(this.O, a) || (this.Ea++, this.B.push(a), this.xb++);
        this.O[a] = b
    };
    Sc.prototype.Ha = function() {
        return new Sc(this)
    };
    var Vc = function(a) {
        Tc(a);
        for (var b = {}, c = 0; c < a.B.length; c++) {
            var d = a.B[c];
            b[d] = a.O[d]
        }
        return b
    };
    Sc.prototype.$a = function(a) {
        Tc(this);
        var b = 0,
            c = this.B,
            d = this.O,
            e = this.xb,
            f = this,
            g = new Oc;
        g.next = function() {
            for (;;) {
                e != f.xb && h(Error("The map has changed since the iterator was created"));
                b >= c.length && h(Nc);
                var g = c[b++];
                return a ? g : d[g]
            }
        };
        return g
    };
    var Uc = function(a, b) {
        return Object.prototype.hasOwnProperty.call(a, b)
    };
    var Yc = function(a) {
            var b = new Sc;
            Wc(a, b, Xc);
            return b
        },
        $c = function(a) {
            var b = [];
            Wc(a, b, Zc);
            return b.join("&")
        },
        Wc = function(a, b, c) {
            for (var d = a.elements, e, f = 0; e = d[f]; f++)
                if (!(e.disabled || "fieldset" == e.tagName.toLowerCase())) {
                    var g = e.name;
                    switch (e.type.toLowerCase()) {
                        case "file":
                        case "submit":
                        case "reset":
                        case "button":
                            break;
                        case "select-multiple":
                            e = ad(e);
                            if (e != l)
                                for (var k, o = 0; k = e[o]; o++) c(b, g, k);
                            break;
                        default:
                            k = ad(e), k != l && c(b, g, k)
                    }
                } d = a.getElementsByTagName("input");
            for (f = 0; e = d[f]; f++) e.form == a && "image" ==
                e.type.toLowerCase() && (g = e.name, c(b, g, e.value), c(b, g + ".x", "0"), c(b, g + ".y", "0"))
        },
        Xc = function(a, b, c) {
            var d = a.get(b);
            d || (d = [], a.set(b, d));
            d.push(c)
        },
        Zc = function(a, b, c) {
            a.push(encodeURIComponent(b) + "=" + encodeURIComponent(c))
        },
        ad = function(a) {
            var b = a.type;
            if (!ca(b)) return l;
            switch (b.toLowerCase()) {
                case "checkbox":
                case "radio":
                    return a.checked ? a.value : l;
                case "select-one":
                    return b = a.selectedIndex, 0 <= b ? a.options[b].value : l;
                case "select-multiple":
                    for (var b = [], c, d = 0; c = a.options[d]; d++) c.selected && b.push(c.value);
                    return b.length ? b : l;
                default:
                    return ca(a.value) ? a.value : l
            }
        },
        bd = function(a) {
            a = a.elements.is_private;
            if (a.type) return ad(a);
            for (var b = 0; b < a.length; b++) {
                var c = ad(a[b]);
                if (c) return c
            }
            return l
        };
    var cd = /<[^>]*>|&[^;]+;/g,
        dd = RegExp("^[^\u0591-\u07ff\ufb1d-\ufdff\ufe70-\ufefc]*[A-Za-z\u00c0-\u00d6\u00d8-\u00f6\u00f8-\u02b8\u0300-\u0590\u0800-\u1fff\u2c00-\ufb1c\ufe00-\ufe6f\ufefd-\uffff]"),
        ed = RegExp("^[^A-Za-z\u00c0-\u00d6\u00d8-\u00f6\u00f8-\u02b8\u0300-\u0590\u0800-\u1fff\u2c00-\ufb1c\ufe00-\ufe6f\ufefd-\uffff]*[\u0591-\u07ff\ufb1d-\ufdff\ufe70-\ufefc]"),
        fd = function(a, b) {
            return ed.test(b ? a.replace(cd, " ") : a)
        };
    var gd = window.yt && window.yt.config_ || {};
    s("yt.config_", gd);
    var hd = window.yt && window.yt.globals_ || {};
    s("yt.globals_", hd);
    var id = window.yt && window.yt.msgs_ || {};
    s("yt.msgs_", id);
    var jd = window.yt && window.yt.timeouts_ || [];
    s("yt.timeouts_", jd);
    var kd = window.yt && window.yt.intervals_ || [];
    s("yt.intervals_", kd);
    var md = function(a) {
            ld(gd, arguments)
        },
        L = function(a, b) {
            return a in gd ? gd[a] : b
        },
        nd = function(a) {
            for (var b = 0, c = arguments.length; b < c; ++b) hd[arguments[b]] = 1
        },
        M = function(a, b) {
            var c = window.setTimeout(a, b);
            jd.push(c);
            return c
        },
        od = function(a, b) {
            var c = window.setInterval(a, b);
            kd.push(c);
            return c
        },
        pd = function(a) {
            window.clearTimeout(a)
        },
        qd = function(a) {
            window.clearInterval(a)
        },
        N = function(a, b, c) {
            var d = b || {};
            if (a = a in id ? id[a] : c)
                for (var e in d) a = a.replace(RegExp("\\$" + e, "gi"), function() {
                    return d[e]
                });
            return a
        },
        ld =
        function(a, b) {
            if (1 < b.length) {
                var c = b[0];
                a[c] = b[1]
            } else {
                var d = b[0];
                for (c in d) a[c] = d[c]
            }
        },
        rd = !!eval("/*@cc_on!@*/false");
    var sd = {},
        td = function(a, b, c, d) {
            if (L("EVENTS_TRACKER_INSTALLED")) {
                var e = sd[a];
                if (!e) {
                    var f = window._gaq._getAsyncTracker("eventsPageTracker");
                    if (!f) return;
                    window._gaq.push(function() {
                        e = f._createEventTracker(a);
                        sd[a] = e
                    })
                }
                var g = c || i,
                    k = d || i;
                window._gaq.push(function() {
                    e._trackEvent(b, g, k)
                })
            }
        };
    var O = function(a, b, c) {
            a.dataset ? a.dataset[ud(b)] = c : a.setAttribute("data-" + b, c)
        },
        P = function(a, b) {
            return a.dataset ? a.dataset[ud(b)] : a.getAttribute("data-" + b)
        },
        vd = function(a, b) {
            a.dataset ? delete a.dataset[ud(b)] : a.removeAttribute("data-" + b)
        },
        wd = {},
        ud = function(a) {
            return wd[a] || (wd[a] = ("" + a).replace(/\-([a-z])/g, function(a, c) {
                return c.toUpperCase()
            }))
        };
    var yd = function(a) {
            var b = a.__yt_uid_key;
            b || (b = xd(), a.__yt_uid_key = b);
            return b
        },
        xd = q("yt.dom.getNextId_");
    if (!xd) {
        xd = function() {
            return ++zd
        };
        s("yt.dom.getNextId_", xd);
        var zd = 0
    }
    var Ad = function(a) {
            var b = a.cloneNode(m);
            "TR" == b.tagName || "SELECT" == b.tagName ? u(a.childNodes, function(a) {
                b.appendChild(Ad(a))
            }) : b.innerHTML = a.innerHTML;
            return b
        },
        Bd = function(a) {
            a = Ad(F(a));
            a.removeAttribute("id");
            return a
        },
        Cd = function(a, b, c) {
            a = F(a);
            b = F(b);
            return !!Kc(a, function(a) {
                return a === b
            }, j, c)
        },
        Dd = function(a, b) {
            return K(a, l, b)
        },
        Ed = function(a, b, c) {
            a = H(a, b, c);
            return a.length ? a[0] : l
        },
        Fd = function(a, b) {
            "disabled" in a && (a.disabled = !b);
            1 == a.nodeType && A(a, "disabled", !b);
            if (a.hasChildNodes())
                for (var c =
                        0, d; d = a.childNodes[c]; ++c) Fd(d, b)
        },
        Hd = function(a) {
            var b = F(a),
                c = Ad(b);
            a.parentNode.appendChild(c);
            c.style.whiteSpace = "normal";
            c.style.lineHeight = "1.5em";
            var d = P(b, "original-html");
            d || (d = b.innerHTML.replace(/^\s+|\s+$/, ""), O(b, "original-html", d));
            for (var a = /<[^>]+>/g, e = d.match(a) || [], f = d.replace(a, "<wbr>").split("<wbr>"), g = function(a) {
                        var b = [],
                            c;
                        for (c = 0; c < a.length; c++) b.push(a[c]), e[c] && b.push(e[c]);
                        e[c] && e[c].match(/^<\s*\//) && b.push(e[c]);
                        b = b.join("");
                        return b.length < d.length ? b + "&hellip;" : b
                    }, k = Gd(c),
                    a = function(a) {
                        c.innerHTML = g(a);
                        a = (c.clientHeight || c.offsetHeight) <= k;
                        c.innerHTML = "";
                        return a
                    }, b = function(a) {
                        for (var b = [], c = 0, d = f.length; c < d && 0 < a; c++) {
                            var e = f[c];
                            b.push(e.substring(0, a));
                            a -= e.length
                        }
                        return b
                    }, o = 0, v = f.join("").length + 1, C = []; o < v;) {
                var E = o + Math.round((v - o) / 2),
                    J = b(E);
                a(J) ? (C = J, o = E + 1) : v = E - 1
            }
            yc(c);
            return g(C)
        },
        Gd = function(a) {
            for (var b = a.innerHTML, c = "", d = 0; 1 > d; d++) c += "&nbsp;<br>";
            a.innerHTML = c;
            c = a.clientHeight || a.offsetHeight;
            a.innerHTML = b;
            return c
        };
    var Id = function(a) {
        if (a = a || q("window.event")) {
            this.type = a.type;
            var b = a.target || a.srcElement;
            b && 3 == b.nodeType && (b = b.parentNode);
            this.target = b;
            if (b = a.relatedTarget) try {
                b = b.nodeName && b
            } catch (c) {
                b = l
            } else "mouseover" == this.type ? b = a.fromElement : "mouseout" == this.type && (b = a.toElement);
            this.relatedTarget = b;
            this.data = a.data;
            this.source = a.source;
            this.origin = a.origin;
            this.state = a.state;
            this.clientX = a.clientX !== i ? a.clientX : a.pageX;
            this.clientY = a.clientY !== i ? a.clientY : a.pageY;
            if (a.pageX || a.pageY) this.pageX = a.pageX,
                this.pageY = a.pageY;
            else if ((a.clientX || a.clientY) && document.body && document.documentElement) this.pageX = a.clientX + document.body.scrollLeft + document.documentElement.scrollLeft, this.pageY = a.clientY + document.body.scrollTop + document.documentElement.scrollTop;
            this.keyCode = a.keyCode ? a.keyCode : a.which;
            this.charCode = a.charCode || ("keypress" == this.type ? this.keyCode : 0);
            0 == this.type.indexOf("touch") && (this.touches = a.touches, this.changedTouches = a.changedTouches);
            0 == this.type.indexOf("gesture") && (this.scale = a.scale,
                this.rotation = a.rotation);
            this.Ka = a
        }
    };
    n = Id.prototype;
    n.type = "";
    n.target = l;
    n.relatedTarget = l;
    n.currentTarget = l;
    n.data = l;
    n.source = l;
    n.origin = l;
    n.state = l;
    n.keyCode = 0;
    n.charCode = 0;
    n.Ka = l;
    n.clientX = 0;
    n.clientY = 0;
    n.pageX = 0;
    n.pageY = 0;
    n.touches = l;
    n.changedTouches = l;
    n.preventDefault = function() {
        this.Ka.returnValue = m;
        this.Ka.preventDefault && this.Ka.preventDefault()
    };
    n.stopPropagation = function() {
        this.Ka.cancelBubble = j;
        this.Ka.stopPropagation && this.Ka.stopPropagation()
    };
    var Jd = q("yt.events.listeners_") || {};
    s("yt.events.listeners_", Jd);
    var Kd = q("yt.events.counter_") || {
        count: 0
    };
    s("yt.events.counter_", Kd);
    var Ld = function(a, b, c, d) {
            return wb(Jd, function(e) {
                return e[0] == a && e[1] == b && e[2] == c && e[4] == !!d
            })
        },
        Q = function(a, b, c, d) {
            if (!a || !a.addEventListener && !a.attachEvent) return "";
            var d = !!d,
                e = Ld(a, b, c, d);
            if (e) return e;
            var e = ++Kd.count + "",
                f = function(b) {
                    b = new Id(b);
                    b.currentTarget = a;
                    return c.call(a, b)
                };
            Jd[e] = [a, b, c, f, d];
            a.addEventListener ? a.addEventListener(b, f, d) : a.attachEvent("on" + b, f);
            return e
        },
        Nd = function(a, b, c, d) {
            var e;
            e = Q(a, b, function() {
                Md(e);
                c.apply(a, arguments)
            }, d)
        },
        Pd = function(a, b) {
            Od(a, "click", b, function(a) {
                return "li" ===
                    a.nodeName.toLowerCase() && j
            })
        },
        Qd = function(a, b, c, d) {
            return Od(a, b, c, function(a) {
                return z(a, d)
            })
        },
        Od = function(a, b, c, d) {
            var e = a || document;
            return Q(e, b, function(a) {
                var b = Kc(a.target, function(a) {
                    return a === e || d(a)
                }, j);
                b && b !== e && (a.currentTarget = b, c.call(b, a))
            })
        },
        Rd = function(a, b, c, d) {
            (a = Ld(a, b, c, !!d)) && Md(a)
        },
        Md = function(a) {
            "string" == typeof a && (a = [a]);
            u(a, function(a) {
                if (a in Jd) {
                    var c = Jd[a],
                        d = c[0],
                        e = c[1],
                        f = c[3],
                        c = c[4];
                    d.removeEventListener ? d.removeEventListener(e, f, c) : d.detachEvent("on" + e, f);
                    delete Jd[a]
                }
            })
        },
        Sd = function(a) {
            a = a || window.event;
            a = a.target || a.srcElement;
            3 == a.nodeType && (a = a.parentNode);
            return a
        },
        Td = function(a) {
            a = a || window.event;
            a.cancelBubble = j;
            a.stopPropagation && a.stopPropagation()
        },
        Ud = function(a) {
            a = a || window.event;
            a.returnValue = m;
            a.preventDefault && a.preventDefault();
            return m
        },
        Vd = function(a) {
            if (document.createEvent) {
                var b = document.createEvent("HTMLEvents");
                b.initEvent("click", j, j);
                a.dispatchEvent(b)
            } else b = document.createEventObject(), a.fireEvent("onclick", b)
        };
    var Wd = function(a) {
            this.o = a
        },
        Xd = /\s*;\s*/;
    n = Wd.prototype;
    n.set = function(a, b, c, d, e, f) {
        /[;=\s]/.test(a) && h(Error('Invalid cookie name "' + a + '"'));
        /[;\r\n]/.test(b) && h(Error('Invalid cookie value "' + b + '"'));
        ca(c) || (c = -1);
        e = e ? ";domain=" + e : "";
        d = d ? ";path=" + d : "";
        f = f ? ";secure" : "";
        c = 0 > c ? "" : 0 == c ? ";expires=" + (new Date(1970, 1, 1)).toUTCString() : ";expires=" + (new Date(oa() + 1E3 * c)).toUTCString();
        this.o.cookie = a + "=" + b + e + d + c + f
    };
    n.get = function(a, b) {
        for (var c = a + "=", d = (this.o.cookie || "").split(Xd), e = 0, f; f = d[e]; e++)
            if (0 == f.indexOf(c)) return f.substr(c.length);
        return b
    };
    n.remove = function(a, b, c) {
        var d = this.bf(a);
        this.set(a, "", 0, b, c);
        return d
    };
    n.De = function() {
        return Yd(this).keys
    };
    n.Ee = function() {
        return Yd(this).wh
    };
    n.G = function() {
        return !this.o.cookie ? 0 : (this.o.cookie || "").split(Xd).length
    };
    n.bf = function(a) {
        return ca(this.get(a))
    };
    n.clear = function() {
        for (var a = Yd(this).keys, b = a.length - 1; 0 <= b; b--) this.remove(a[b])
    };
    var Yd = function(a) {
            for (var a = (a.o.cookie || "").split(Xd), b = [], c = [], d, e, f = 0; e = a[f]; f++) d = e.indexOf("="), -1 == d ? (b.push(""), c.push(e)) : (b.push(e.substring(0, d)), c.push(e.substring(d + 1)));
            return {
                keys: b,
                wh: c
            }
        },
        Zd = new Wd(document);
    Zd.Vj = 3950;
    var $d = function(a, b, c, d, e) {
            Zd.set("" + a, b, c, d || "/", e || "localhost")
        },
        ae = function(a, b) {
            return Zd.get("" + a, b)
        },
        be = function(a, b, c) {
            return Zd.remove("" + a, b || "/", c || "localhost")
        };
    var R = function() {
        var a = ae(this.cf);
        if (a)
            for (var a = unescape(a).split("&"), b = 0; b < a.length; b++) {
                var c = a[b].split("="),
                    d = c[0];
                (c = c[1]) && (ce[d] = c.toString())
            }
    };
    aa(R);
    var ce = q("yt.prefs.UserPrefs.prefs_") || {};
    s("yt.prefs.UserPrefs.prefs_", ce);
    R.prototype.cf = "PREF";
    var de = function(a) {
            /^f([1-9][0-9]*)$/.test(a) && h("ExpectedRegexMatch: " + a)
        },
        ee = function(a) {
            /^\w+$/.test(a) || h("ExpectedRegexMismatch: " + a)
        },
        fe = function(a) {
            a = ce[a] !== i ? ce[a].toString() : l;
            return a != l && /^[A-Fa-f0-9]+$/.test(a) ? parseInt(a, 16) : l
        };
    R.prototype.get = function(a, b) {
        ee(a);
        de(a);
        var c = ce[a] !== i ? ce[a].toString() : l;
        return c != l ? c : b ? b : ""
    };
    R.prototype.set = function(a, b) {
        ee(a);
        de(a);
        b == l && h("ExpectedNotNull");
        ce[a] = b.toString()
    };
    var ge = function(a, b) {
            return !!((fe("f" + (Math.floor(b / 31) + 1)) || 0) & 1 << b % 31)
        },
        he = function(a, b) {
            var c = "f" + (Math.floor(a / 31) + 1),
                d = 1 << a % 31,
                e = fe(c) || 0,
                e = b ? e | d : e & ~d;
            0 == e ? delete ce[c] : (d = e.toString(16), ce[c] = d.toString())
        };
    R.prototype.remove = function(a) {
        ee(a);
        de(a);
        delete ce[a]
    };
    R.prototype.save = function(a) {
        var a = 86400 * (a || 7),
            b = this.cf,
            c = [],
            d;
        for (d in ce) c.push(d + "=" + escape(ce[d]));
        $d(b, c.join("&"), a)
    };
    R.prototype.clear = function() {
        ce = {}
    };
    var ie = function(a, b, c, d, e, f, g) {
            var k = [];
            a && k.push(a, ":");
            c && (k.push("//"), b && k.push(b, "@"), k.push(c), d && k.push(":", d));
            e && k.push(e);
            f && k.push("?", f);
            g && k.push("#", g);
            return k.join("")
        },
        je = RegExp("^(?:([^:/?#.]+):)?(?://(?:([^/?#]*)@)?([\\w\\d\\-\\u0100-\\uffff.%]*)(?::([0-9]+))?)?([^?#]+)?(?:\\?([^#]*))?(?:#(.*))?$"),
        ke = function() {
            var a = document.location.href.match(je);
            return ie(a[1], a[2], a[3], a[4])
        },
        le = function(a) {
            a = a.match(je);
            return ie(l, l, l, l, a[5], a[6], a[7])
        },
        me = function(a, b) {
            for (var c in b) {
                var d =
                    c,
                    e = b[c],
                    f = a;
                if (da(e))
                    for (var g = 0; g < e.length; g++) f.push("&", d), "" !== e[g] && f.push("=", ta(e[g]));
                else e != l && (f.push("&", d), "" !== e && f.push("=", ta(e)))
            }
            return a
        },
        ne = function(a) {
            a = me([], a);
            a[0] = "";
            return a.join("")
        };
    var oe = function(a) {
            "?" == a.charAt(0) && (a = a.substr(1));
            for (var a = a.split("&"), b = {}, c = 0, d = a.length; c < d; c++) {
                var e = a[c].split("=");
                if (1 == e.length && e[0] || 2 == e.length) {
                    var f = e[0],
                        e = decodeURIComponent((e[1] || "").replace(/\+/g, " "));
                    f in b ? da(b[f]) ? fb(b[f], e) : b[f] = [b[f], e] : b[f] = e
                }
            }
            return b
        },
        pe = function(a) {
            return -1 != a.indexOf("?") ? (a = (a || "").split("#")[0], a = a.split("?", 2), oe(1 < a.length ? a[1] : a[0])) : {}
        },
        qe = function(a) {
            "#" == a.charAt(0) && (a = "!" == a.charAt(1) ? a.substr(2) : a.substr(1));
            return oe(a)
        },
        re = function(a,
            b) {
            var c = me([a], b);
            if (c[1]) {
                var d = c[0],
                    e = d.indexOf("#");
                0 <= e && (c.push(d.substr(e)), c[0] = d = d.substr(0, e));
                e = d.indexOf("?");
                0 > e ? c[1] = "?" : e == d.length - 1 && (c[1] = i)
            }
            return c.join("")
        },
        se = function(a, b) {
            var c = a.split("?", 2),
                a = c[0],
                c = oe(c[1] || ""),
                d;
            for (d in b) c[d] = b[d];
            return re(a, c)
        },
        te = function(a) {
            a = (a = a.match(je)[3] || l) && decodeURIComponent(a);
            a = a === l ? l : a.split(".").reverse();
            return (a === l ? m : "com" == a[0] && a[1].match(/^youtube(?:-nocookie)?$/) ? j : m) || (a === l ? m : "google" == a[1] ? j : "google" == a[2] ? "au" == a[0] && "com" ==
                a[1] ? j : "uk" == a[0] && "co" == a[1] ? j : m : m)
        };
    var xe = function(a) {
            a = a || {};
            this.url = a.url || this.url;
            this.urlV8 = a.url_v8 || this.urlV8;
            this.urlV9As2 = a.url_v9as2 || this.urlV9As2;
            this.minVersion = a.min_version || this.minVersion;
            this.args = a.args || yb(ue);
            this.assets = a.assets || {};
            this.attrs = a.attrs || yb(ve);
            this.params = a.params || yb(we);
            this.fallback = a.fallback || this.fallback;
            this.html5 = a.html5 || this.html5;
            this.disable = a.disable || {}
        },
        ue = {
            enablejsapi: 1
        },
        ve = {
            width: "640",
            height: "385"
        },
        we = {
            allowscriptaccess: "always",
            allowfullscreen: "true",
            bgcolor: "#000000"
        };
    n = xe.prototype;
    n.url = "";
    n.urlV8 = "";
    n.urlV9As2 = "";
    n.minVersion = "8.0.0";
    n.html5 = m;
    var ze = function() {
        this.mc = [];
        ye(this)
    };
    aa(ze);
    n = ze.prototype;
    n.F = 0;
    n.qa = 0;
    n.rev = 0;
    n.jd = "";
    n.oa = 0;
    n.load = function(a) {
        3 <= this.oa ? a(this) : this.mc.push(a)
    };
    n.setVersion = function(a) {
        this.F = a[0];
        this.qa = a[1];
        this.rev = a[2]
    };
    n.isSupported = function(a, b, c) {
        a = "string" == typeof a ? a.split(".") : [a, b, c];
        a[0] = parseInt(a[0], 10) || 0;
        a[1] = parseInt(a[1], 10) || 0;
        a[2] = parseInt(a[2], 10) || 0;
        return this.F > a[0] || this.F == a[0] && this.qa > a[1] || this.F == a[0] && this.qa == a[1] && this.rev >= a[2]
    };
    var Ce = function(a) {
            return -1 < a.jd.indexOf("Gnash") && -1 == a.jd.indexOf("AVM2") || 9 == a.F && 1 == a.qa || 9 == a.F && 0 == a.qa && 1 == a.rev ? m : 9 <= a.F
        },
        De = function(a) {
            return -1 < navigator.userAgent.indexOf("Sony/COM2") && !a.isSupported(9, 1, 58) ? m : j
        },
        ye = function(a) {
            if (3 > a.oa)
                if (1 > a.oa) {
                    var b = q("window.navigator.plugins"),
                        c = q("window.navigator.mimeTypes"),
                        b = b && b["Shockwave Flash"],
                        c = c && c["application/x-shockwave-flash"],
                        c = b && c && c.enabledPlugin && b.description || "";
                    if (b = c) {
                        var d = b.indexOf("Shockwave Flash");
                        0 <= d && (b = b.substr(d +
                            15));
                        for (var d = b.split(" "), e = "", b = "", f = 0, g = d.length; f < g; f++)
                            if (e)
                                if (b) break;
                                else b = d[f];
                        else e = d[f];
                        e = e.split(".");
                        d = parseInt(e[0], 10) || 0;
                        e = parseInt(e[1], 10) || 0;
                        f = 0;
                        if ("r" == b.charAt(0) || "d" == b.charAt(0)) f = parseInt(b.substr(1), 10) || 0;
                        b = [d, e, f]
                    } else b = [0, 0, 0];
                    a.jd = c;
                    a.setVersion(b);
                    a.oa = 1;
                    0 < a.F ? Ee(a) : ye(a)
                } else 2 > a.oa ? Fe(a) : Ee(a)
        };
    ze.prototype.df = function(a) {
        a ? (a = a.split(" ")[1].split(","), a = [parseInt(a[0], 10) || 0, parseInt(a[1], 10) || 0, parseInt(a[2], 10) || 0]) : a = [0, 0, 0];
        this.setVersion(a);
        this.oa = 2;
        0 < this.F ? Ee(this) : ye(this)
    };
    var Ee = function(a) {
            if (3 > a.oa) {
                a.oa = 3;
                for (var b = 0, c = a.mc.length; b < c; b++) a.mc[b](a);
                a.mc = []
            }
        },
        Fe = function(a) {
            var b, c, d, e;
            if (rd) {
                try {
                    b = new ActiveXObject("ShockwaveFlash.ShockwaveFlash")
                } catch (f) {
                    b = l
                }
                b || a.df("")
            } else d = document.getElementsByTagName("body")[0], e = document.createElement("object"), e.setAttribute("type", "application/x-shockwave-flash"), b = d.appendChild(e);
            var g = r(a.df, a),
                k = 0,
                o = function() {
                    if (b && "GetVariable" in b) try {
                        c = b.GetVariable("$version")
                    } catch (a) {
                        c = ""
                    }
                    c || 10 <= k ? (d && e && d.removeChild(e),
                        g(c || "")) : (k++, M(o, 10))
                };
            M(o, 0)
        };
    var Ge = function(a, b, c) {
            if ((a = F(a)) && b && c) {
                c instanceof xe || (c = new xe(c));
                var d = yb(c.attrs),
                    e = yb(c.params);
                e.flashvars = ne(c.args);
                c = [];
                if (rd) {
                    d.classid = "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000";
                    e.movie = b;
                    c.push("<object ");
                    for (var f in d) c.push(f, '="', d[f], '"');
                    c.push(">");
                    for (f in e) c.push('<param name="', f, '" value="', e[f], '">');
                    c.push("</object>")
                } else {
                    d.type = "application/x-shockwave-flash";
                    d.src = b;
                    c.push("<embed ");
                    for (f in d) c.push(f, '="', d[f], '"');
                    for (f in e) c.push(f, '="', e[f], '"');
                    c.push(" />")
                }
                a.innerHTML =
                    c.join("")
            }
        },
        He = function(a, b) {
            if (a) {
                a instanceof xe || (a = new xe(a));
                var c = !!b,
                    d = F(a.attrs.id),
                    e = d ? d.parentNode : l;
                if (!d || !e) M(function() {
                    He(a)
                }, 50);
                else {
                    if (window != window.top) {
                        var f = l;
                        if (document.referrer) {
                            var g = document.referrer.substring(0, 128);
                            te(g) || (f = g)
                        } else f = "unknown";
                        f && (c = j, a.args.framer = f)
                    }
                    Ie(function(b) {
                        if (b.isSupported(a.minVersion)) {
                            var f = ""; - 1 < navigator.userAgent.indexOf("Sony/COM2") || (f = d.src || d.movie);
                            Ce(b) ? (f != a.url || c) && Ge(e, a.url, a) : De(b) ? (f != a.urlV9As2 || c) && Ge(e, a.urlV9As2, a) : (f !=
                                a.urlV8 || c) && Ge(e, a.urlV8, a)
                        } else rd && b.isSupported(6, 0, 65) ? (b = new xe({
                            url: "//s.ytimg.com/yt/swf/expressInstall-vflIE9HEf.swf",
                            args: {
                                MMredirectURL: window.location,
                                MMplayerType: "ActiveX",
                                MMdoctitle: document.title
                            }
                        }), Ge(e, b.url, b)) : 0 == b.F && a.fallback ? a.fallback() : 0 == b.F && a.fallbackMessage ? a.fallbackMessage() : e.innerHTML = '<div id="flash-upgrade">' + N("FLASH_UPGRADE") + "</div>"
                    })
                }
            }
        },
        Ie = function(a) {
            ze.getInstance().load(function(b) {
                var c = R.getInstance();
                c.set("fv", [b.F, b.qa, b.rev].join("."));
                c.save();
                a(b)
            })
        };
    var Je = function() {};
    var Ke = function() {
        this.Z = [];
        this.Y = {}
    };
    t(Ke, Je);
    n = Ke.prototype;
    n.Oe = 1;
    n.bc = 0;
    n.Vb = function(a, b, c) {
        var d = this.Y[a];
        d || (d = this.Y[a] = []);
        var e = this.Oe;
        this.Z[e] = a;
        this.Z[e + 1] = b;
        this.Z[e + 2] = c;
        this.Oe = e + 3;
        d.push(e);
        return e
    };
    n.kd = function(a) {
        if (0 != this.bc) return this.ac || (this.ac = []), this.ac.push(a), m;
        var b = this.Z[a];
        if (b) {
            var c = this.Y[b];
            if (c) {
                var d = Sa(c, a);
                0 <= d && bb(c, d)
            }
            delete this.Z[a];
            delete this.Z[a + 1];
            delete this.Z[a + 2]
        }
        return !!b
    };
    n.gc = function(a, b) {
        var c = this.Y[a];
        if (c) {
            this.bc++;
            for (var d = gb(arguments, 1), e = 0, f = c.length; e < f; e++) {
                var g = c[e];
                this.Z[g + 1].apply(this.Z[g + 2], d)
            }
            this.bc--;
            if (this.ac && 0 == this.bc)
                for (; c = this.ac.pop();) this.kd(c);
            return 0 != e
        }
        return m
    };
    n.clear = function(a) {
        if (a) {
            var b = this.Y[a];
            b && (u(b, this.kd, this), delete this.Y[a])
        } else this.Z.length = 0, this.Y = {}
    };
    n.G = function(a) {
        if (a) {
            var b = this.Y[a];
            return b ? b.length : 0
        }
        a = 0;
        for (b in this.Y) a += this.G(b);
        return a
    };
    var Le = q("yt.pubsub.instance_") || new Ke;
    Ke.prototype.subscribe = Ke.prototype.Vb;
    Ke.prototype.unsubscribeByKey = Ke.prototype.kd;
    Ke.prototype.publish = Ke.prototype.gc;
    Ke.prototype.clear = Ke.prototype.clear;
    s("yt.pubsub.instance_", Le);
    var Me = function(a, b, c) {
            var d = q("yt.pubsub.instance_");
            return d ? d.subscribe(a, function() {
                var a = arguments;
                M(function() {
                    b.apply(c || p, a)
                }, 0)
            }, c) : 0
        },
        Ne = function(a, b) {
            var c = q("yt.pubsub.instance_");
            return c ? c.publish.apply(c, arguments) : m
        };
    var Oe, Qe = function(a, b, c) {
            this.ga = a;
            Pe && (this.u = b);
            this.Ua = c || window;
            this.la = this.Ua.location;
            this.Vg = this.la.href.split("#")[0];
            this.me = r(this.Xg, this)
        },
        Re = B && 8 <= document.documentMode || Nb && D("1.9.2") || Ob && D("532.1"),
        Pe = B && !Re;
    Qe.prototype.zd = function(a, b) {
        this.ad && (Md(this.ad), delete this.ad);
        this.Wa && (qd(this.Wa), delete this.Wa);
        if (a) {
            this.X = Se(this);
            if (Pe) {
                var c = this.u.contentWindow.document.body;
                (!c || !c.innerHTML) && Te(this, this.X)
            }
            b || this.ga(this.X);
            Re ? this.ad = Q(this.Ua, "hashchange", this.me) : this.Wa = od(this.me, 200)
        }
    };
    Qe.prototype.Xg = function() {
        if (Pe) {
            var a;
            a = (a = this.u.contentWindow.document.body) ? decodeURIComponent(a.innerHTML.substring(1).replace(/\+/g, " ")) : "";
            a != this.X ? (this.X = a, Ue(this, a), this.ga(a)) : (a = Se(this), a != this.X && (this.X = a, Te(this, a), this.ga(a)))
        } else a = Se(this), a != this.X && (this.X = a, this.ga(a))
    };
    var Se = function(a) {
            var a = a.la.href,
                b = a.indexOf("#");
            return 0 > b ? "" : a.substring(b + 1)
        },
        Ue = function(a, b) {
            var c = a.Vg + "#" + b,
                d = a.la.href;
            d == c || d + "#" == c || (a.la.href = c)
        },
        Te = function(a, b) {
            var c = a.u.contentWindow.document,
                d = c.body ? c.body.innerHTML : "",
                e = "#" + ta(b);
            d != e && (d = ["<title>", za(window.document.title || ""), "</title><body>", e, "</body>"], c.open("text/html"), c.write(d.join("")), c.close())
        };
    Qe.prototype.add = function(a, b, c) {
        this.X = "" + a;
        Pe && Te(this, a);
        Ue(this, a);
        c || this.ga(this.X)
    };
    var Ve = function() {
            this.gf = oa()
        },
        We = new Ve;
    Ve.prototype.set = function(a) {
        this.gf = a
    };
    Ve.prototype.reset = function() {
        this.set(oa())
    };
    Ve.prototype.get = function() {
        return this.gf
    };
    var Xe = function(a) {
        this.bh = a || "";
        this.dh = We
    };
    n = Xe.prototype;
    n.ve = j;
    n.fh = j;
    n.eh = j;
    n.$c = m;
    n.gh = m;
    var Ye = function(a) {
            return 10 > a ? "0" + a : "" + a
        },
        Ze = function(a, b) {
            var c = (a.te - b) / 1E3,
                d = c.toFixed(3),
                e = 0;
            if (1 > c) e = 2;
            else
                for (; 100 > c;) e++, c *= 10;
            for (; 0 < e--;) d = " " + d;
            return d
        },
        $e = function(a) {
            Xe.call(this, a)
        };
    t($e, Xe);
    var bf = function(a) {
            return af(a || arguments.callee.caller, [])
        },
        af = function(a, b) {
            var c = [];
            if ($a(b, a)) c.push("[...circular reference...]");
            else if (a && 50 > b.length) {
                c.push(cf(a) + "(");
                for (var d = a.arguments, e = 0; e < d.length; e++) {
                    0 < e && c.push(", ");
                    var f;
                    f = d[e];
                    switch (typeof f) {
                        case "object":
                            f = f ? "object" : "null";
                            break;
                        case "string":
                            break;
                        case "number":
                            f = "" + f;
                            break;
                        case "boolean":
                            f = f ? "true" : "false";
                            break;
                        case "function":
                            f = (f = cf(f)) ? f : "[fn]";
                            break;
                        default:
                            f = typeof f
                    }
                    40 < f.length && (f = f.substr(0, 40) + "...");
                    c.push(f)
                }
                b.push(a);
                c.push(")\n");
                try {
                    c.push(af(a.caller, b))
                } catch (g) {
                    c.push("[exception trying to get caller]\n")
                }
            } else a ? c.push("[...long stack...]") : c.push("[end]");
            return c.join("")
        },
        cf = function(a) {
            if (df[a]) return df[a];
            a = "" + a;
            if (!df[a]) {
                var b = /function ([^\(]+)/.exec(a);
                df[a] = b ? b[1] : "[Anonymous]"
            }
            return df[a]
        },
        df = {};
    var ef = function(a, b, c, d, e) {
        this.reset(a, b, c, d, e)
    };
    ef.prototype.Zc = l;
    ef.prototype.Yc = l;
    var ff = 0;
    ef.prototype.reset = function(a, b, c, d, e) {
        "number" == typeof e || ff++;
        this.te = d || oa();
        this.Ia = a;
        this.vh = b;
        this.se = c;
        delete this.Zc;
        delete this.Yc
    };
    ef.prototype.getLevel = function() {
        return this.Ia
    };
    ef.prototype.lc = function(a) {
        this.Ia = a
    };
    ef.prototype.getMessage = function() {
        return this.vh
    };
    var gf = function(a) {
        this.ma = a
    };
    gf.prototype.ic = l;
    gf.prototype.Ia = l;
    gf.prototype.yd = l;
    gf.prototype.Bb = l;
    var hf = function(a, b) {
        this.name = a;
        this.value = b
    };
    hf.prototype.toString = function() {
        return this.name
    };
    var jf = new hf("SHOUT", 1200),
        kf = new hf("SEVERE", 1E3),
        lf = new hf("WARNING", 900),
        mf = new hf("INFO", 800),
        nf = new hf("CONFIG", 700);
    gf.prototype.getName = function() {
        return this.ma
    };
    gf.prototype.getParent = function() {
        return this.ic
    };
    gf.prototype.lc = function(a) {
        this.Ia = a
    };
    gf.prototype.getLevel = function() {
        return this.Ia
    };
    var of = function(a) {
        if (a.Ia) return a.Ia;
        if (a.ic) return of(a.ic);
        Pa("Root logger has no level set.");
        return l
    };
    gf.prototype.log = function(a, b, c) {
        if (a.value >= of (this).value) {
            a = this.Qh(a, b, c);
            b = "log:" + a.getMessage();
            p.console && (p.console.timeStamp ? p.console.timeStamp(b) : p.console.markTimeline && p.console.markTimeline(b));
            p.msWriteProfilerMark && p.msWriteProfilerMark(b);
            for (b = this; b;) {
                var c = b,
                    d = a;
                if (c.Bb)
                    for (var e = 0, f = i; f = c.Bb[e]; e++) f(d);
                b = b.getParent()
            }
        }
    };
    gf.prototype.Qh = function(a, b, c) {
        var d = new ef(a, "" + b, this.ma);
        if (c) {
            d.Zc = c;
            var e;
            var f = arguments.callee.caller;
            try {
                var g;
                var k = q("window.location.href");
                if (ga(c)) g = {
                    message: c,
                    name: "Unknown error",
                    lineNumber: "Not available",
                    fileName: k,
                    stack: "Not available"
                };
                else {
                    var o, v, C = m;
                    try {
                        o = c.lineNumber || c.fi || "Not available"
                    } catch (E) {
                        o = "Not available", C = j
                    }
                    try {
                        v = c.fileName || c.filename || c.sourceURL || k
                    } catch (J) {
                        v = "Not available", C = j
                    }
                    g = C || !c.lineNumber || !c.fileName || !c.stack ? {
                        message: c.message,
                        name: c.name,
                        lineNumber: o,
                        fileName: v,
                        stack: c.stack || "Not available"
                    } : c
                }
                e = "Message: " + za(g.message) + '\nUrl: <a href="view-source:' + g.fileName + '" target="_new">' + g.fileName + "</a>\nLine: " + g.lineNumber + "\n\nBrowser stack:\n" + za(g.stack + "-> ") + "[end]\n\nJS stack traversal:\n" + za(bf(f) + "-> ")
            } catch (fa) {
                e = "Exception trying to expose exception! You win, we lose. " + fa
            }
            d.Yc = e
        }
        return d
    };
    gf.prototype.info = function(a, b) {
        this.log(mf, a, b)
    };
    var pf = {},
        qf = l,
        rf = function() {
            qf || (qf = new gf(""), pf[""] = qf, qf.lc(nf))
        },
        sf = function(a) {
            rf();
            var b;
            if (!(b = pf[a])) {
                b = new gf(a);
                var c = a.lastIndexOf("."),
                    d = a.substr(c + 1),
                    c = sf(a.substr(0, c));
                c.yd || (c.yd = {});
                c.yd[d] = b;
                b.ic = c;
                pf[a] = b
            }
            return b
        };
    var tf = function() {
            this.oh = r(this.th, this);
            this.Wb = new $e;
            this.Wb.ve = m;
            this.Fe = this.Wb.$c = m;
            this.ue = "";
            this.Pg = {}
        },
        wf = function() {
            var a = uf;
            if (j != a.Fe) {
                rf();
                var b = qf,
                    c = a.oh;
                b.Bb || (b.Bb = []);
                b.Bb.push(c);
                a.Fe = j
            }
        };
    tf.prototype.th = function(a) {
        if (!this.Pg[a.se]) {
            var b;
            b = this.Wb;
            var c = [];
            c.push(b.bh, " ");
            if (b.ve) {
                var d = new Date(a.te);
                c.push("[", Ye(d.getFullYear() - 2E3) + Ye(d.getMonth() + 1) + Ye(d.getDate()) + " " + Ye(d.getHours()) + ":" + Ye(d.getMinutes()) + ":" + Ye(d.getSeconds()) + "." + Ye(Math.floor(d.getMilliseconds() / 10)), "] ")
            }
            b.fh && c.push("[", Ze(a, b.dh.get()), "s] ");
            b.eh && c.push("[", a.se, "] ");
            b.gh && c.push("[", a.getLevel().name, "] ");
            c.push(a.getMessage(), "\n");
            b.$c && a.Zc && c.push(a.Yc, "\n");
            b = c.join("");
            if (xf && xf.firebug) switch (a.getLevel()) {
                case jf:
                    xf.info(b);
                    break;
                case kf:
                    xf.error(b);
                    break;
                case lf:
                    xf.warn(b);
                    break;
                default:
                    xf.debug(b)
            } else xf ? xf.log(b) : window.opera ? window.opera.postError(b) : this.ue += b
        }
    };
    var uf = l,
        xf = window.console,
        yf = function() {
            uf || (uf = new tf); - 1 != window.location.href.indexOf("Debug=true") && wf()
        };
    var zf = l,
        Af = function(a) {
            zf || (yf(), wf(), uf.Wb.$c = j, zf = sf("yt.debug"), zf.lc(mf));
            zf.log(mf, "yt.history.StateMonitor: " + a, i)
        };
    var Bf = function(a, b) {
            this.ga = a;
            this.Ua = b || window;
            this.la = this.Ua.location;
            this.Zg = r(this.sh, this)
        },
        Cf = !!window.history.pushState && (!Ob || Ob && D("534.11"));
    Bf.prototype.zd = function(a, b) {
        Af("setEnabled " + a);
        this.ed && (Md(this.ed), delete this.ed);
        this.Wa && (qd(this.Wa), delete this.Wa);
        !a || !Cf ? Af("disabling (supported = " + Cf + ")") : (Af("enabling"), this.Yb = this.la.href, b || this.ga(this.Yb), this.ed = Q(this.Ua, "popstate", this.Zg))
    };
    Bf.prototype.sh = function(a) {
        var b = this.la.href,
            a = a.state;
        Af("handlePopState_ " + b + " " + a);
        if (a || b != this.Yb) Af("handlePopState_ navCallback"), this.Yb = b, this.ga(b, a)
    };
    Bf.prototype.add = function(a, b, c) {
        if (a || b) a = a || this.la.href, this.Ua.history.pushState(b, "", a), this.Yb = a, c || this.ga(a, b)
    };
    var Ef = function(a) {
            var a = a || "hash",
                b = q("yt.history.instance_");
            b || ("state" == a ? (b = new Bf(Df), Bf.prototype.setEnabled = Bf.prototype.zd, Bf.prototype.add = Bf.prototype.add) : (b = new Qe(Df, F("legacy-history-iframe")), Qe.prototype.setEnabled = Qe.prototype.zd, Qe.prototype.add = Qe.prototype.add), Oe = b, s("yt.history.instance_", Oe));
            return b
        },
        Df = function(a, b) {
            Ne("navigate", a, b)
        };
    var Ff = {
            GOOGLE_IMA: "//www.google.com/jsapi",
            GOOGLE_MAPS_API: "//maps.google.com/maps/api/js?sensor=false",
            GOOGLE_LANGUAGE_API_VIRTUAL_KEYBOARD: "//www.google.com/jsapi?key=youtube-internal-vk",
            GOOGLE_LANGUAGE_API_INPUT_TOOLS: "//www.google.com/jsapi?key=youtube-internal-it"
        },
        Gf = {},
        Hf = {},
        If = {},
        Kf = function(a) {
            s("yt.net.apiloader.onApiLoaded_" + a, function() {
                Jf(a)
            })
        },
        Jf = function(a) {
            Hf[a] = j;
            u(If[a], function(a) {
                a.call()
            });
            delete If[a]
        },
        Lf = function(a, b) {
            var c = Ff[a];
            if (c)
                if (Hf[a]) b.call();
                else if (If[a] || (If[a] = []), If[a].push(b), !Gf[a]) {
                Kf(a);
                var c = re(c, {
                        callback: "yt.net.apiloader.onApiLoaded_" + a
                    }),
                    d = document.createElement("script");
                d.src = c;
                document.body.appendChild(d);
                Gf[a] = j
            }
        };
    var Mf, Nf, Of = l,
        Pf = m,
        Qf = "";
    var Rf, Sf, Tf = m,
        Uf = "";
    var Vf = function(a) {
            a = "" + a;
            if (/^\s*$/.test(a) ? 0 : /^[\],:{}\s\u2028\u2029]*$/.test(a.replace(/\\["\\\/bfnrtu]/g, "@").replace(/"[^"\\\n\r\u2028\u2029\x00-\x08\x10-\x1f\x80-\x9f]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, "]").replace(/(?:^|:|,)(?:[\s\u2028\u2029]*\[)+/g, ""))) try {
                return eval("(" + a + ")")
            } catch (b) {}
            h(Error("Invalid JSON string: " + a))
        },
        Wf = function(a) {
            return eval("(" + a + ")")
        },
        Xf = function(a) {
            this.oc = a
        },
        Zf = function(a, b) {
            var c = [];
            Yf(a, b, c);
            return c.join("")
        },
        Yf = function(a, b, c) {
            switch (typeof b) {
                case "string":
                    $f(b,
                        c);
                    break;
                case "number":
                    c.push(isFinite(b) && !isNaN(b) ? b : "null");
                    break;
                case "boolean":
                    c.push(b);
                    break;
                case "undefined":
                    c.push("null");
                    break;
                case "object":
                    if (b == l) {
                        c.push("null");
                        break
                    }
                    if (da(b)) {
                        var d = b.length;
                        c.push("[");
                        for (var e = "", f = 0; f < d; f++) c.push(e), e = b[f], Yf(a, a.oc ? a.oc.call(b, "" + f, e) : e, c), e = ",";
                        c.push("]");
                        break
                    }
                    c.push("{");
                    d = "";
                    for (f in b) Object.prototype.hasOwnProperty.call(b, f) && (e = b[f], "function" != typeof e && (c.push(d), $f(f, c), c.push(":"), Yf(a, a.oc ? a.oc.call(b, f, e) : e, c), d = ","));
                    c.push("}");
                    break;
                case "function":
                    break;
                default:
                    h(Error("Unknown type: " + typeof b))
            }
        },
        ag = {
            '"': '\\"',
            "\\": "\\\\",
            "/": "\\/",
            "\u0008": "\\b",
            "\u000c": "\\f",
            "\n": "\\n",
            "\r": "\\r",
            "\t": "\\t",
            "\x0B": "\\u000b"
        },
        bg = /\uffff/.test("\uffff") ? /[\\\"\x00-\x1f\x7f-\uffff]/g : /[\\\"\x00-\x1f\x7f-\xff]/g,
        $f = function(a, b) {
            b.push('"', a.replace(bg, function(a) {
                if (a in ag) return ag[a];
                var b = a.charCodeAt(0),
                    e = "\\u";
                16 > b ? e += "000" : 256 > b ? e += "00" : 4096 > b && (e += "0");
                return ag[a] = e + b.toString(16)
            }), '"')
        };
    var cg = l;
    "undefined" != typeof XMLHttpRequest ? cg = function() {
        return new XMLHttpRequest
    } : "undefined" != typeof ActiveXObject && (cg = function() {
        return new ActiveXObject("Microsoft.XMLHTTP")
    });
    var dg = function(a) {
        switch (a && "status" in a ? a.status : -1) {
            case 0:
            case 200:
            case 204:
            case 304:
                return j;
            default:
                return m
        }
    };
    var eg = function(a, b, c, d, e) {
            var f = cg && cg();
            if ("open" in f) {
                f.onreadystatechange = function() {
                    4 == (f && "readyState" in f ? f.readyState : 0) && b && b(f)
                };
                c = (c || "GET").toUpperCase();
                d = d || "";
                f.open(c, a, j);
                a = "POST" == c;
                if (e)
                    for (var g in e) f.setRequestHeader(g, e[g]), "content-type" == g.toLowerCase() && (a = m);
                a && f.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                f.send(d);
                return f
            }
        },
        S = function(a, b) {
            var c = b.format || "JSON";
            b.Fb && (a = "//" + document.location.hostname + a);
            var d = b.g;
            d && (a = se(a, d));
            var d = b.sa ||
                "",
                e = b.z;
            d && e && h(Error());
            e && (d = ne(e));
            eg(a, function(a) {
                var d = dg(a),
                    e = l;
                if (d || 400 <= a.status && 500 > a.status) e = fg(c, a);
                if (d) a: {
                    switch (c) {
                        case "XML":
                            d = 0 == parseInt(e && e.return_code, 10);
                            break a;
                        case "RAW":
                            d = j;
                            break a
                    }
                    d = !!e
                }
                var e = e || {},
                    o = b.j || p;
                d ? b.f && b.f.call(o, a, e) : b.r && b.r.call(o, a, e);
                b.aa && b.aa.call(o, a, e)
            }, b.method, d, b.headers)
        },
        fg = function(a, b) {
            var c = l;
            switch (a) {
                case "JSON":
                    var d = b.responseText,
                        e = b.getResponseHeader("Content-Type") || "";
                    d && 0 <= e.indexOf("json") && (c = Wf(d));
                    break;
                case "XML":
                    if (d = (d = b.responseXML) ?
                        gg(d) : l) c = {}, u(d.getElementsByTagName("*"), function(a) {
                        c[a.tagName] = hg(a)
                    })
            }
            return c
        },
        jg = function(a, b) {
            var c = b.onComplete || l,
                d = b.onException || l,
                e = b.onError || l,
                f = b.update || l,
                g = b.json || m;
            return eg(a, function(a) {
                if (dg(a)) {
                    var b = a.responseXML,
                        v = b ? gg(b) : l,
                        b = !(!b || !v),
                        C, E;
                    if (b && (C = ig(v, "return_code"), E = ig(v, "html_content"), 0 == C)) {
                        f && E && (F(f).innerHTML = E);
                        var J = ig(v, "js_content");
                        if (J) {
                            var fa = document.createElement("script");
                            fa.text = J;
                            document.getElementsByTagName("head")[0].appendChild(fa)
                        }
                    }
                    c && (b ? (b = ig(v,
                        "redirect_on_success"), C && b ? window.location = b : ((v = ig(v, 0 == C ? "success_message" : "error_message")) && alert(v), a = g ? eval("(" + E + ")") : a, 0 == C ? c(a) : d && d(a))) : a.responseText && c(a))
                } else e && e(a)
            }, b.method || "POST", b.postBody || l, b.headers || l)
        },
        gg = function(a) {
            return !a ? l : (a = ("responseXML" in a ? a.responseXML : a).getElementsByTagName("root")) && 0 < a.length ? a[0] : l
        },
        ig = function(a, b) {
            if (!a) return l;
            var c = a.getElementsByTagName(b);
            return c && 0 < c.length ? hg(c[0]) : l
        },
        hg = function(a) {
            var b = "";
            u(a.childNodes, function(a) {
                b += a.nodeValue
            });
            return b
        },
        T = q("yt.net.ajax.tokenMap_") || {};
    s("yt.net.ajax.tokenMap_", T);
    var kg = {},
        lg = {},
        mg = function(a, b, c) {
            a = F(a);
            c = c || a[ia] || (a[ia] = ++ja);
            c in kg || (kg[c] = []);
            kg[c].push([a, b]);
            lg[c] = m;
            return c
        },
        ng = function(a, b) {
            var a = F(a),
                c = b || a[ia] || (a[ia] = ++ja),
                d = kg[c];
            d && (kg[c] = Ta(d, function(b) {
                return b[0] != a
            }))
        },
        og = function(a) {
            a in kg && !lg[a] && (u(kg[a], function(a) {
                var c = a[0],
                    a = a[1];
                c && "IMG" == c.tagName && (c.onload = "", c.src = a)
            }), kg[a] = [], lg[a] = j)
        };
    var qg = function(a, b, c) {
            var d = "scriptload-" + Ja(a),
                e = document.getElementById(d),
                f = e && P(e, "loaded"),
                g = e && !f;
            if (f && !c) return b && b(), e;
            if (g && !c) return b && Me(d, b), e;
            e && (d = "scriptload-" + (Math.floor(2147483648 * Math.random()).toString(36) + Math.abs(Math.floor(2147483648 * Math.random()) ^ oa()).toString(36)));
            b && Me(d, b);
            var k = pg(a, d, function() {
                if (!P(k, "loaded")) {
                    O(k, "loaded", "true");
                    Ne(d);
                    var a = d,
                        b = q("yt.pubsub.instance_");
                    b && b.clear(a)
                }
            });
            return k
        },
        pg = function(a, b, c) {
            var d = document.createElement("script");
            d.id =
                b;
            d.onload = c;
            d.onreadystatechange = function() {
                if ("loaded" == d.readyState || "complete" == d.readyState) d.onload()
            };
            d.src = a;
            a = document.getElementsByTagName("head")[0];
            a.insertBefore(d, a.firstChild);
            return d
        };
    var rg = function(a, b, c, d) {
        this.top = a;
        this.right = b;
        this.bottom = c;
        this.left = d
    };
    rg.prototype.Ha = function() {
        return new rg(this.top, this.right, this.bottom, this.left)
    };
    rg.prototype.toString = function() {
        return "(" + this.top + "t, " + this.right + "r, " + this.bottom + "b, " + this.left + "l)"
    };
    rg.prototype.contains = function(a) {
        return !this || !a ? m : a instanceof rg ? a.left >= this.left && a.right <= this.right && a.top >= this.top && a.bottom <= this.bottom : a.x >= this.left && a.x <= this.right && a.y >= this.top && a.y <= this.bottom
    };
    rg.prototype.expand = function(a, b, c, d) {
        ha(a) ? (this.top -= a.top, this.right += a.right, this.bottom += a.bottom, this.left -= a.left) : (this.top -= a, this.right += b, this.bottom += c, this.left -= d);
        return this
    };
    var sg = function(a, b, c, d) {
        this.left = a;
        this.top = b;
        this.width = c;
        this.height = d
    };
    sg.prototype.Ha = function() {
        return new sg(this.left, this.top, this.width, this.height)
    };
    sg.prototype.toString = function() {
        return "(" + this.left + ", " + this.top + " - " + this.width + "w x " + this.height + "h)"
    };
    sg.prototype.contains = function(a) {
        return a instanceof sg ? this.left <= a.left && this.left + this.width >= a.left + a.width && this.top <= a.top && this.top + this.height >= a.top + a.height : a.x >= this.left && a.x <= this.left + this.width && a.y >= this.top && a.y <= this.top + this.height
    };
    var tg = function(a, b, c) {
            a.style[La(c)] = b
        },
        ug = function(a, b) {
            var c = gc(a);
            return c.defaultView && c.defaultView.getComputedStyle && (c = c.defaultView.getComputedStyle(a, l)) ? c[b] || c.getPropertyValue(b) : ""
        },
        vg = function(a, b) {
            return a.currentStyle ? a.currentStyle[b] : l
        },
        wg = function(a, b) {
            return ug(a, b) || vg(a, b) || a.style && a.style[b]
        },
        xg = function(a) {
            var b = a.getBoundingClientRect();
            B && (a = a.ownerDocument, b.left -= a.documentElement.clientLeft + a.body.clientLeft, b.top -= a.documentElement.clientTop + a.body.clientTop);
            return b
        },
        yg = function(a) {
            if (B && !cc(8)) return a.offsetParent;
            for (var b = gc(a), c = wg(a, "position"), d = "fixed" == c || "absolute" == c, a = a.parentNode; a && a != b; a = a.parentNode)
                if (c = wg(a, "position"), d = d && "static" == c && a != b.documentElement && a != b.body, !d && (a.scrollWidth > a.clientWidth || a.scrollHeight > a.clientHeight || "fixed" == c || "absolute" == c || "relative" == c)) return a;
            return l
        },
        Bg = function(a) {
            for (var b = new rg(0, Infinity, Infinity, 0), c = hc(a), d = c.o.body, e = c.o.documentElement, f = !Ob && kc(c.o) ? c.o.documentElement : c.o.body; a = yg(a);)
                if ((!B ||
                        0 != a.clientWidth) && (!Ob || 0 != a.clientHeight || a != d) && a != d && a != e && "visible" != wg(a, "overflow")) {
                    var g = zg(a),
                        k;
                    k = a;
                    if (Nb && !D("1.9")) {
                        var o = parseFloat(ug(k, "borderLeftWidth"));
                        if (Ag(k)) var v = k.offsetWidth - k.clientWidth - o - parseFloat(ug(k, "borderRightWidth")),
                            o = o + v;
                        k = new qb(o, parseFloat(ug(k, "borderTopWidth")))
                    } else k = new qb(k.clientLeft, k.clientTop);
                    g.x += k.x;
                    g.y += k.y;
                    b.top = Math.max(b.top, g.y);
                    b.right = Math.min(b.right, g.x + a.clientWidth);
                    b.bottom = Math.min(b.bottom, g.y + a.clientHeight);
                    b.left = Math.max(b.left,
                        g.x)
                } d = f.scrollLeft;
            f = f.scrollTop;
            b.left = Math.max(b.left, d);
            b.top = Math.max(b.top, f);
            c = nc(c.o.parentWindow || c.o.defaultView || window);
            b.right = Math.min(b.right, d + c.width);
            b.bottom = Math.min(b.bottom, f + c.height);
            return 0 <= b.top && 0 <= b.left && b.bottom > b.top && b.right > b.left ? b : l
        },
        zg = function(a) {
            var b, c = gc(a),
                d = wg(a, "position"),
                e = Nb && c.getBoxObjectFor && !a.getBoundingClientRect && "absolute" == d && (b = c.getBoxObjectFor(a)) && (0 > b.screenX || 0 > b.screenY),
                f = new qb(0, 0),
                g;
            b = c ? 9 == c.nodeType ? c : gc(c) : document;
            g = B && !cc(9) &&
                !Lc(hc(b)) ? b.body : b.documentElement;
            if (a == g) return f;
            if (a.getBoundingClientRect) b = xg(a), a = Mc(hc(c)), f.x = b.left + a.x, f.y = b.top + a.y;
            else if (c.getBoxObjectFor && !e) b = c.getBoxObjectFor(a), a = c.getBoxObjectFor(g), f.x = b.screenX - a.screenX, f.y = b.screenY - a.screenY;
            else {
                b = a;
                do {
                    f.x += b.offsetLeft;
                    f.y += b.offsetTop;
                    b != a && (f.x += b.clientLeft || 0, f.y += b.clientTop || 0);
                    if (Ob && "fixed" == wg(b, "position")) {
                        f.x += c.body.scrollLeft;
                        f.y += c.body.scrollTop;
                        break
                    }
                    b = b.offsetParent
                } while (b && b != a);
                if (Mb || Ob && "absolute" == d) f.y -= c.body.offsetTop;
                for (b = a;
                    (b = yg(b)) && b != c.body && b != g;)
                    if (f.x -= b.scrollLeft, !Mb || "TR" != b.tagName) f.y -= b.scrollTop
            }
            return f
        },
        Cg = function(a, b) {
            "number" == typeof a && (a = (b ? Math.round(a) : a) + "px");
            return a
        },
        Eg = function(a) {
            if ("none" != wg(a, "display")) return Dg(a);
            var b = a.style,
                c = b.display,
                d = b.visibility,
                e = b.position;
            b.visibility = "hidden";
            b.position = "absolute";
            b.display = "inline";
            a = Dg(a);
            b.display = c;
            b.position = e;
            b.visibility = d;
            return a
        },
        Dg = function(a) {
            var b = a.offsetWidth,
                c = a.offsetHeight,
                d = Ob && !b && !c;
            return (!ca(b) || d) && a.getBoundingClientRect ?
                (a = xg(a), new sb(a.right - a.left, a.bottom - a.top)) : new sb(b, c)
        },
        Fg = function(a) {
            var b = zg(a),
                a = Eg(a);
            return new sg(b.x, b.y, a.width, a.height)
        },
        Ag = function(a) {
            return "rtl" == wg(a, "direction")
        },
        Gg = function(a, b) {
            if (/^\d+px?$/.test(b)) return parseInt(b, 10);
            var c = a.style.left,
                d = a.runtimeStyle.left;
            a.runtimeStyle.left = a.currentStyle.left;
            a.style.left = b;
            var e = a.style.pixelLeft;
            a.style.left = c;
            a.runtimeStyle.left = d;
            return e
        },
        Hg = {
            thin: 2,
            medium: 4,
            thick: 6
        },
        Ig = function(a, b) {
            if ("none" == vg(a, b + "Style")) return 0;
            var c = vg(a,
                b + "Width");
            return c in Hg ? Hg[c] : Gg(a, c)
        };
    var Jg = function(a, b) {
            if ((a = F(a)) && a.style) a.style.display = b ? "" : "none", A(a, "hid", !b)
        },
        Kg = function(a) {
            a = F(a);
            return !a ? m : !("none" == a.style.display || z(a, "hid"))
        },
        Lg = function(a) {
            if (a = F(a)) Kg(a) ? (a.style.display = "none", w(a, "hid")) : (a.style.display = "", x(a, "hid"))
        },
        U = function(a) {
            u(arguments, function(a) {
                Jg(a, j)
            })
        },
        V = function(a) {
            u(arguments, function(a) {
                Jg(a, m)
            })
        },
        Mg = function(a) {
            u(arguments, Lg)
        };
    var Ng = {},
        Og = 0,
        Pg = function(a, b) {
            var c = new Image,
                d = "" + Og++;
            Ng[d] = c;
            c.onload = c.onerror = function() {
                b && Ng[d] && b();
                delete Ng[d]
            };
            c.src = a;
            c = eval("null")
        };
    var W = q("yt.timing") || {};
    s("yt.timing", W);
    W.Kh = 0;
    W.Ue = 0;
    W.jc = function(a, b) {
        var c = W.timer || {};
        c[a] = b ? b : oa();
        W.timer = c
    };
    W.info = function(a, b) {
        var c = W.info_args || {};
        c[a] = b;
        W.info_args = c
    };
    W.pc = function(a) {
        var a = a || L("TIMING_ACTION"),
            b = W.timer || {},
            c = W.info_args || {},
            d = b.start,
            e = "",
            f = [],
            g = [];
        delete b.start;
        W.srt && (e = "&srt=" + W.srt);
        b.aft && b.plev && (b.aft = Math.min(b.aft, b.plev));
        for (var k in b) f.push(k + "." + Math.round(b[k] - d));
        for (k in c) g.push(k + "=" + c[k]);
        b.vr && b.gv && f.push("vl." + Math.round(b.vr - b.gv));
        !b.aft && b.vr && b.cl ? b.cl > b.vr ? f.push("aft." + Math.round(b.cl - d)) : f.push("aft." + Math.round(b.vr - d)) : !b.aft && b.vr ? f.push("aft." + Math.round(b.vr - d)) : b.aft || f.push("aft." + Math.round(b.ol - d));
        Pg(["https:" ==
            window.location.protocol ? "https://gg.google.com/csi" : "http://csi.gstatic.com/csi", "?v=2&s=youtube&action=", a, e, "&", g.join("&"), "&rt=", f.join(",")
        ].join(""))
    };
    W.xd = function() {
        var a = L("TIMING_ACTION"),
            b = W.timer || {};
        a && b.start && (W.wff && -1 != a.indexOf("ajax") && b.vr && b.cl ? W.pc() : W.wff && -1 == a.indexOf("ajax") && b.vr ? W.pc() : !W.wff && (b.ol || b.aft) && W.pc())
    };
    W.ef = function() {
        W.jc("ol");
        W.xd()
    };
    W.$h = function(a) {
        var b = ++W.Kh;
        "undefined" != typeof a && 4 > a && W.Ue++;
        4 == W.Ue && W.jc("tn_c4");
        1 != b && 5 != b && 10 != b && 20 != b && 30 != b || W.jc("tn" + b)
    };
    var X = function(a, b, c) {
            td(a, b || "null");
            Qg("a=" + a + (b ? "&" + b : "").replace(/\//g, "&"), c)
        },
        Qg = function(a, b) {
            Pg("/gen_204?" + a, b)
        },
        Rg = function(a, b, c, d) {
            a = re("/sharing_services", {
                name: a,
                v: b,
                locale: c,
                feature: d
            });
            Pg(a)
        };
    var Sg = [/ytimg\.com\/\S+\/(hq)?default.jpg$/, /\/master-[^/]+png$/, /\/pixel-[^/]+gif$/, /\/www-core-[^/]+(js|css)$/],
        Tg = m,
        Vg = function() {
            var a = Math.random();
            0.66 < a ? Qg(Ug("script")) : 0.33 < a ? Qg(Ug("img")) : (a = Ug("link", function(a) {
                if ("stylesheet" == a.rel) return a.href
            }), Qg(a))
        },
        Ug = function(a, b) {
            var c = Ua(Wg(a, b), Xg),
                c = c.join(","),
                c = c.substring(0, 1948),
                d = Xg(document.location.href);
            return ne({
                a: "included-resources",
                tag: a,
                source: d,
                resources: c
            })
        },
        Wg = function(a, b) {
            var c = b || function(a) {
                    return a.src
                },
                d = H(a, l, document),
                e = {};
            u(d, function(a) {
                a = c(a);
                !(a in e) && !Yg(a) && (e[a] = j)
            });
            return vb(e)
        },
        Yg = function(a) {
            return !a || Va(Sg, function(b) {
                return b.test(a)
            })
        },
        Xg = function(a) {
            a = a.substring(0, 100);
            return a.replace(",", "")
        };
    var Zg = function(a, b, c, d, e, f, g, k) {
        var o, v = c.offsetParent;
        if (v) {
            var C = "HTML" == v.tagName || "BODY" == v.tagName;
            if (!C || "static" != wg(v, "position")) o = zg(v), C || (o = rb(o, new qb(v.scrollLeft, v.scrollTop)))
        }
        v = Fg(a);
        if (C = Bg(a)) {
            var E = new sg(C.left, C.top, C.right - C.left, C.bottom - C.top),
                C = Math.max(v.left, E.left),
                J = Math.min(v.left + v.width, E.left + E.width);
            if (C <= J) {
                var fa = Math.max(v.top, E.top),
                    E = Math.min(v.top + v.height, E.top + E.height);
                fa <= E && (v.left = C, v.top = fa, v.width = J - C, v.height = E - fa)
            }
        }
        C = hc(a);
        fa = hc(c);
        if (C.o != fa.o) {
            var J =
                C.o.body,
                fa = fa.o.parentWindow || fa.o.defaultView,
                E = new qb(0, 0),
                Ac = gc(J) ? gc(J).parentWindow || gc(J).defaultView : window,
                vf = J;
            do {
                var Xa;
                if (Ac == fa) Xa = zg(vf);
                else {
                    var ka = vf;
                    Xa = new qb;
                    if (1 == ka.nodeType)
                        if (ka.getBoundingClientRect) ka = xg(ka), Xa.x = ka.left, Xa.y = ka.top;
                        else {
                            var Ae = Mc(hc(ka)),
                                ka = zg(ka);
                            Xa.x = ka.x - Ae.x;
                            Xa.y = ka.y - Ae.y
                        }
                    else {
                        var Ae = "function" == ba(ka.Ge),
                            Be = ka;
                        ka.targetTouches ? Be = ka.targetTouches[0] : Ae && ka.Ge().targetTouches && (Be = ka.Ge().targetTouches[0]);
                        Xa.x = Be.clientX;
                        Xa.y = Be.clientY
                    }
                }
                E.x += Xa.x;
                E.y +=
                    Xa.y
            } while (Ac && Ac != fa && (vf = Ac.frameElement) && (Ac = Ac.parent));
            J = rb(E, zg(J));
            B && !Lc(C) && (J = rb(J, Mc(C)));
            v.left += J.x;
            v.top += J.y
        }
        a = (b & 4 && Ag(a) ? b ^ 2 : b) & -5;
        b = new qb(a & 2 ? v.left + v.width : v.left, a & 1 ? v.top + v.height : v.top);
        o && (b = rb(b, o));
        e && (b.x += (a & 2 ? -1 : 1) * e.x, b.y += (a & 1 ? -1 : 1) * e.y);
        var G;
        if (g && (G = Bg(c)) && o) G.top -= o.y, G.right -= o.x, G.bottom -= o.y, G.left -= o.x;
        a: {
            o = b.Ha();e = 0;a = (d & 4 && Ag(c) ? d ^ 2 : d) & -5;d = Eg(c);k = k ? k.Ha() : d.Ha();
            if (f || 0 != a) a & 2 ? o.x -= k.width + (f ? f.right : 0) : f && (o.x += f.left),
            a & 1 ? o.y -= k.height + (f ? f.bottom : 0) : f && (o.y += f.top);
            if (g) {
                if (G) {
                    f = o;
                    e = 0;
                    if (65 == (g & 65) && (f.x < G.left || f.x >= G.right)) g &= -2;
                    if (132 == (g & 132) && (f.y < G.top || f.y >= G.bottom)) g &= -5;
                    f.x < G.left && g & 1 && (f.x = G.left, e |= 1);
                    f.x < G.left && f.x + k.width > G.right && g & 16 && (k.width = Math.max(k.width - (f.x + k.width - G.right), 0), e |= 4);
                    f.x + k.width > G.right && g & 1 && (f.x = Math.max(G.right - k.width, G.left), e |= 1);
                    g & 2 && (e |= (f.x < G.left ? 16 : 0) | (f.x + k.width > G.right ? 32 : 0));
                    f.y < G.top && g & 4 && (f.y = G.top, e |= 2);
                    f.y >= G.top && f.y + k.height > G.bottom && g & 32 && (k.height = Math.max(k.height - (f.y + k.height -
                        G.bottom), 0), e |= 8);
                    f.y + k.height > G.bottom && g & 4 && (f.y = Math.max(G.bottom - k.height, G.top), e |= 2);
                    g & 8 && (e |= (f.y < G.top ? 64 : 0) | (f.y + k.height > G.bottom ? 128 : 0));
                    g = e
                } else g = 256;
                e = g;
                if (e & 496) {
                    c = e;
                    break a
                }
            }
            f = Nb && (Gb || Sb) && D("1.9");o instanceof qb ? (g = o.x, o = o.y) : (g = o, o = i);c.style.left = Cg(g, f);c.style.top = Cg(o, f);
            if (!(d == k || (!d || !k ? 0 : d.width == k.width && d.height == k.height))) f = Lc(hc(gc(c))),
            B && (!f || !D("8")) ? (g = c.style, f ? (B ? (f = Gg(c, vg(c, "paddingLeft")), d = Gg(c, vg(c, "paddingRight")), o = Gg(c, vg(c, "paddingTop")), G = Gg(c, vg(c,
                    "paddingBottom")), f = new rg(o, d, G, f)) : (f = ug(c, "paddingLeft"), d = ug(c, "paddingRight"), o = ug(c, "paddingTop"), G = ug(c, "paddingBottom"), f = new rg(parseFloat(o), parseFloat(d), parseFloat(G), parseFloat(f))), B ? (d = Ig(c, "borderLeft"), o = Ig(c, "borderRight"), G = Ig(c, "borderTop"), c = Ig(c, "borderBottom"), c = new rg(G, o, c, d)) : (d = ug(c, "borderLeftWidth"), o = ug(c, "borderRightWidth"), G = ug(c, "borderTopWidth"), c = ug(c, "borderBottomWidth"), c = new rg(parseFloat(G), parseFloat(o), parseFloat(c), parseFloat(d))), g.pixelWidth = k.width - c.left -
                f.left - f.right - c.right, g.pixelHeight = k.height - c.top - f.top - f.bottom - c.bottom) : (g.pixelWidth = k.width, g.pixelHeight = k.height)) : (c = c.style, Nb ? c.MozBoxSizing = "border-box" : Ob ? c.WebkitBoxSizing = "border-box" : c.boxSizing = "border-box", c.width = Math.max(k.width, 0) + "px", c.height = Math.max(k.height, 0) + "px");c = e
        }
        return c
    };
    var $g = {},
        ah = "ontouchstart" in document,
        bh = function(a, b) {
            var c = $g[a].maxNumParents[b],
                d;
            0 < c ? d = c : -1 != a.indexOf("mouse") && (d = 2);
            return d
        },
        ch = function(a, b, c) {
            return Kc(b, function(b) {
                return z(b, a)
            }, j, c) || l
        },
        dh = function(a) {
            if ("HTML" != a.target.tagName && a.type in $g) {
                var b = $g[a.type],
                    c;
                for (c in b.Y) {
                    var d = bh(a.type, c),
                        e = ch(c, a.target, d);
                    if (e) {
                        var f = j;
                        b.checkRelatedTarget[c] && a.relatedTarget && Kc(a.relatedTarget, function(a) {
                            return a == e
                        }, j, d) && (f = m);
                        f && b.gc(c, e, a.type, a)
                    }
                }
            }
        };
    Q(document, "click", dh);
    Q(document, "mouseover", dh);
    Q(document, "mouseout", dh);
    Q(document, "keydown", dh);
    Q(document, "keyup", dh);
    Q(document, "keypress", dh);
    Q(document, "cut", dh);
    Q(document, "paste", dh);
    ah && (Q(document, "touchstart", dh), Q(document, "touchend", dh), Q(document, "touchcancel", dh));
    var eh = window.yt && window.yt.uix && window.yt.uix.widgets_ || {};
    s("yt.uix.widgets_", eh);
    var fh = function(a) {
        var a = a.getInstance(),
            b = Y(a);
        !(b in eh) && a.ud() && (a.T(), eh[b] = a)
    };
    var gh = function() {
        this.K = {}
    };
    n = gh.prototype;
    n.td = !!eval("/*@cc_on!@*/false");
    n.ud = function() {
        return j
    };
    n.addBehavior = function(a, b, c, d, e) {
        var c = Y(this, c),
            f = r(b, this);
        a in $g || ($g[a] = new Ke, $g[a].maxNumParents = {}, $g[a].checkRelatedTarget = {});
        a = $g[a];
        a.Vb(c, f);
        a.maxNumParents[c] = d;
        a.checkRelatedTarget[c] = e;
        this.K[b] = f
    };
    n.pa = function(a, b, c) {
        var d = this.getData(a, b);
        if (d && (d = q(d))) {
            var e = gb(arguments, 2);
            hb(e, 0, 0, a);
            d.apply(l, e)
        }
    };
    n.getData = function(a, b) {
        return P(a, b)
    };
    n.setData = function(a, b, c) {
        O(a, b, c)
    };
    var hh = function(a, b) {
            return Dd(b, Y(a))
        },
        Y = function(a, b) {
            return "yt-uix" + (a.P ? "-" + a.P : "") + (b ? "-" + b : "")
        };
    var ih = function() {
        this.K = {}
    };
    t(ih, gh);
    aa(ih);
    ih.prototype.P = "button";
    ih.prototype.T = function() {
        this.addBehavior("click", this.Ja);
        this.addBehavior("keydown", this.cc);
        this.addBehavior("keypress", this.sd)
    };
    ih.prototype.Ja = function(a) {
        if (a && !a.disabled) {
            if (z(a, Y(this, "toggle"))) {
                var b = Dd(a, Y(this, "group"));
                if (b && this.getData(b, "button-toggle-group")) {
                    var b = jc(Y(this), b),
                        c = Y(this, "toggled");
                    u(b, function(b) {
                        b != a ? x(b, c) : w(a, c)
                    })
                } else pb(a, Y(this, "toggled"))
            }
            jh(this, a) && (kh(this, a), a.focus());
            this.pa(a, "button-action")
        }
    };
    ih.prototype.cc = function(a, b, c) {
        if (b = jh(this, a)) {
            var d = function(a) {
                var b = "";
                a.tagName && (b = a.tagName.toLowerCase());
                return "ul" == b || "table" == b
            };
            if (d = d(b) ? b : Fc(b, d)) {
                var d = d.tagName.toLowerCase(),
                    e;
                "ul" == d ? e = this.Wh : "table" == d && (e = this.Vh);
                e && lh(this, a, b, c, r(e, this))
            }
        }
    };
    var lh = function(a, b, c, d, e) {
        var f = Kg(c),
            g = 9 == d.keyCode;
        g || 32 == d.keyCode || 13 == d.keyCode ? (d = mh(a, c)) ? (b = Bc(d), "a" == b.tagName.toLowerCase() ? window.location = b.href : Vd(b)) : g && nh(a, b) : f ? 27 == d.keyCode ? (mh(a, c), nh(a, b)) : e(b, c, d) : (a = z(b, Y(a, "reverse")) ? 38 : 40, d.keyCode == a && (Vd(b), d.preventDefault()))
    };
    ih.prototype.sd = function(a, b, c) {
        a = jh(this, a);
        Kg(a) && c.preventDefault()
    };
    var mh = function(a, b) {
            var c = Y(a, "menu-item-highlight"),
                d = I(c, b);
            d && x(d, c);
            return d
        },
        oh = function(a, b, c) {
            w(c, Y(a, "menu-item-highlight"));
            b.setAttribute("aria-activedescendant", c.getAttribute("id"))
        };
    ih.prototype.Vh = function(a, b, c) {
        var d = mh(this, b),
            b = Ed("table", l, b),
            e = Ed("tr", l, b),
            e = H("td", l, e).length,
            b = H("td", l, b),
            d = ph(d, b, e, c); - 1 != d && (oh(this, a, b[d]), c.preventDefault())
    };
    ih.prototype.Wh = function(a, b, c) {
        if (40 == c.keyCode || 38 == c.keyCode) {
            var d = mh(this, b),
                b = H("li", l, b),
                d = ph(d, b, 1, c);
            oh(this, a, b[d]);
            c.preventDefault()
        }
    };
    var ph = function(a, b, c, d) {
            var e = b.length,
                a = Sa(b, a);
            if (-1 == a)
                if (38 == d.keyCode) a = e - c;
                else {
                    if (37 == d.keyCode || 38 == d.keyCode || 40 == d.keyCode) a = 0
                }
            else 39 == d.keyCode ? (a % c == c - 1 && (a -= c), a += 1) : 37 == d.keyCode ? (0 == a % c && (a += c), a -= 1) : 38 == d.keyCode ? (a < c && (a += e), a -= c) : 40 == d.keyCode && (a >= e - c && (a -= e), a += c);
            return a
        },
        kh = function(a, b) {
            var c = jh(a, b),
                d = qh(a, c);
            d && d != b ? (nh(a, d), M(r(a.Bd, a, b), 1)) : Kg(c) ? nh(a, b) : a.Bd(b)
        };
    ih.prototype.zb = function(a) {
        if (!Hb || !z(a, Y(this, "masked"))) return l;
        var b = a.iframeMask;
        b || (b = document.createElement("iframe"), b.src = 'javascript:""', b.className = Y(this, "menu-mask"), a.iframeMask = b);
        return b
    };
    var rh = function(a, b, c) {
        var d = Dd(b, Y(a, "group")),
            e = !!a.getData(b, "button-menu-ignore-group"),
            d = d && !e ? d : b,
            e = 5,
            f = 4;
        z(b, Y(a, "reverse")) && (e = 4, f = 5);
        z(b, "flip") && (z(b, Y(a, "reverse")) ? (e = 6, f = 7) : (e = 7, f = 6));
        var g;
        if (a.getData(b, "button-has-sibling-menu")) {
            var k = yg(d);
            B && !D("8") && (k = l);
            k && (g = Fg(k), g = new rg(-g.top, g.left, g.top, -g.left))
        }
        k = new qb(0, 1);
        if (a = a.zb(b)) b = Eg(c), a.style.width = b.width + "px", a.style.height = b.height + "px", Zg(d, e, a, f, k, g);
        Zg(d, e, c, f, k, g)
    };
    ih.prototype.Bd = function(a) {
        if (a) {
            var b = jh(this, a);
            if (b) {
                a.setAttribute("aria-pressed", "true");
                a.setAttribute("aria-expanded", "true");
                b.originalParentNode = b.parentNode;
                b.activeButtonNode = a;
                b.parentNode.removeChild(b);
                this.getData(a, "button-has-sibling-menu") ? a.parentNode.appendChild(b) : document.body.appendChild(b);
                var c = Dd(a, Y(this, "group")),
                    d = !!this.getData(a, "button-menu-ignore-group");
                b.style.minWidth = (c && !d ? c.offsetWidth - 2 : a.offsetWidth - 2) + "px";
                (d = this.zb(a)) && document.body.appendChild(d);
                rh(this,
                    a, b);
                U(b);
                this.pa(a, "button-menu-action", j);
                w(a, Y(this, "active"));
                c && w(c, Y(this, "group-active"));
                c = r(this.xh, this, a);
                b = Q(document, "click", c);
                c = Q(document, "contextmenu", c);
                this.setData(a, "button-listener", b);
                this.setData(a, "button-context-menu-listener", c)
            }
        }
    };
    var nh = function(a, b) {
        if (b) {
            var c = jh(a, b);
            if (c) {
                b.setAttribute("aria-pressed", "false");
                b.setAttribute("aria-expanded", "false");
                V(c);
                a.pa(b, "button-menu-action", m);
                var d = a.zb(b);
                M(function() {
                    d && d.parentNode && d.parentNode.removeChild(d);
                    c.originalParentNode && (c.parentNode.removeChild(c), c.originalParentNode.appendChild(c), c.originalParentNode = l, c.activeButtonNode = l)
                }, 1)
            }
            var e = Dd(b, Y(a, "group"));
            x(b, Y(a, "active"));
            e && x(e, Y(a, "group-active"));
            if (e = a.getData(b, "button-listener")) Md(e), vd(b, "button-listener");
            if (e = a.getData(b, "button-context-menu-listener")) Md(e), vd(b, "button-context-menu-listener")
        }
    };
    ih.prototype.getContent = function(a) {
        return I(Y(this, "content"), a)
    };
    var qh = function(a, b) {
        return Dd(b.activeButtonNode || b.parentNode, Y(a))
    };
    ih.prototype.xh = function(a, b) {
        var c = Sd(b),
            d = Dd(c, Y(this));
        if (d) {
            var d = jh(this, d),
                e = jh(this, a);
            if (d == e) return
        }
        if (!Dd(c, Y(this, "menu")) || z(c, Y(this, "menu-item")) || z(c, Y(this, "menu-close")))
            if (nh(this, a), (d = Dd(c, Y(this, "menu"))) && this.getData(a, "button-menu-indicate-selected")) e = I(Y(this, "content"), a), Dc(e, Jc(c)), e = Y(this, "menu-item-selected"), (d = I(e, d)) && x(d, e), w(c.parentNode, e)
    };
    var jh = function(a, b) {
        if (!b.widgetMenu) {
            var c = a.getData(b, "button-menu-id"),
                c = c && F(c),
                d = Y(a, "menu");
            c ? w(c, d) : c = I(d, b);
            b.widgetMenu = c
        }
        return b.widgetMenu
    };
    var sh = function() {
        this.K = {}
    };
    t(sh, gh);
    aa(sh);
    sh.prototype.P = "char-counter";
    sh.prototype.T = function() {
        this.addBehavior("keydown", this.Cd, "input");
        this.addBehavior("paste", this.Cd, "input");
        this.addBehavior("cut", this.Cd, "input")
    };
    sh.prototype.Cd = function(a) {
        var b = hh(this, a);
        if (b) {
            var c = "true" == this.getData(b, "count-char-by-size"),
                d = parseInt(this.getData(b, "char-limit"), 10);
            isNaN(d) || 0 >= d || M(r(function() {
                var e = parseInt(a.getAttribute("maxlength"), 10);
                if (!isNaN(e)) {
                    var f = th(a, c);
                    if (c) {
                        if (f > e) {
                            var g = a.value,
                                k = g.length,
                                o = 0,
                                e = f - e,
                                f = "",
                                v = 0;
                            do f += g[k - o], v = unescape(encodeURIComponent(f)).length, o++; while (v < e);
                            a.value = a.value.substring(0, k - o)
                        }
                    } else f > e && (a.value = a.value.substring(0, e))
                }
                g = parseInt(this.getData(b, "warn-at-chars-remaining"),
                    10);
                isNaN(g) && (g = 0);
                k = d - th(a, c);
                A(b, Y(this, "maxed-out"), k < g);
                I(Y(this, "remaining"), b).innerHTML = k
            }, this), 0)
        }
    };
    var th = function(a, b) {
        var c = a.value;
        return b ? unescape(encodeURIComponent(c)).length : c.length
    };
    var uh = function() {
        this.K = {}
    };
    t(uh, gh);
    uh.prototype.ud = function() {
        return this.td && 0 == $b.indexOf("6") ? m : j
    };
    uh.prototype.show = function(a) {
        var b = hh(this, a);
        if (b) {
            w(b, Y(this, "active"));
            var c = vh(this, a, b);
            if (c) {
                c.cardTargetNode = a;
                c.cardRootNode = b;
                wh(this, a, c);
                var d = Y(this, "card-visible");
                M(function() {
                    U(c);
                    w(c, d)
                }, 10);
                this.pa(b, "card-action", a)
            }
        }
    };
    var vh = function(a, b, c) {
            var d = Y(a, "card"),
                e = d + yd(c),
                f = F(e);
            if (f) return f;
            c = a.mb(c);
            if (!c) return l;
            f = document.createElement("div");
            f.id = e;
            f.className = d;
            d = document.createElement("div");
            d.className = Y(a, "card-border");
            b = a.getData(b, "orientation") || "horizontal";
            e = document.createElement("div");
            e.className = "yt-uix-card-border-arrow yt-uix-card-border-arrow-" + b;
            var g = document.createElement("div");
            g.className = Y(a, "card-body");
            a = document.createElement("div");
            a.className = "yt-uix-card-body-arrow yt-uix-card-body-arrow-" +
                b;
            yc(c);
            g.appendChild(c);
            d.appendChild(a);
            d.appendChild(g);
            f.appendChild(e);
            f.appendChild(d);
            document.body.appendChild(f);
            return f
        },
        wh = function(a, b, c) {
            var d = a.getData(b, "orientation") || "horizontal",
                e = a.getData(b, "position"),
                f = !!a.getData(b, "force-position"),
                d = "horizontal" == d,
                g = "bottomright" == e || "bottomleft" == e,
                e = "topright" == e || "bottomright" == e,
                k, o;
            e && g ? (o = 7, k = 4) : e && !g ? (o = 6, k = 5) : !e && g ? (o = 5, k = 6) : (o = 4, k = 7);
            var v = Ag(document.body),
                C = Ag(b);
            v != C && (o ^= 2);
            var E;
            d ? (C = b.offsetHeight / 2 - 24, E = new qb(-12, b.offsetHeight +
                6)) : (C = b.offsetWidth / 2 - 12, E = new qb(b.offsetWidth + 6, -12));
            var J = l;
            f || (J = 10);
            var fa = Y(a, "card-flip"),
                a = Y(a, "card-reverse");
            A(c, fa, e);
            A(c, a, g);
            J = Zg(b, o, c, k, E, l, J);
            !f && J && (J & 48 && (e = !e, o ^= 2, k ^= 2), J & 192 && (g = !g, o ^= 1, k ^= 1), A(c, fa, e), A(c, a, g), Zg(b, o, c, k, E));
            b = I("yt-uix-card-body-arrow", c);
            f = I("yt-uix-card-border-arrow", c);
            c = Eg(c);
            C = Math.max(6, Math.min(C, (d ? c.height : c.width) - 24 - 6));
            c = d ? g ? "top" : "bottom" : !v && e || v && !e ? "left" : "right";
            b.setAttribute("style", "");
            f.setAttribute("style", "");
            b.style[c] = C + "px";
            f.style[c] =
                C + "px"
        };
    uh.prototype.ea = function(a) {
        var b = hh(this, a);
        if (b && (a = vh(this, a, b))) x(b, Y(this, "active")), x(a, Y(this, "card-visible")), V(a), a.cardTargetNode = l, a.cardRootNode = l
    };
    var xh = function(a, b) {
        var c = hh(a, b);
        return !c ? m : z(c, Y(a, "active"))
    };
    uh.prototype.mb = function(a) {
        var b = a.cardContentNode;
        if (!b) {
            var c = Y(this, "content"),
                d = Y(this, "card-content"),
                b = I(c, a);
            y(b, c, d);
            a.cardContentNode = b
        }
        return b
    };
    uh.prototype.Qe = {
        Fh: 200,
        Ph: 200
    };
    var yh = function() {
        this.K = {}
    };
    t(yh, uh);
    aa(yh);
    yh.prototype.P = "clickcard";
    yh.prototype.T = function() {
        this.addBehavior("click", this.Uh, "target");
        this.addBehavior("click", this.Th, "close")
    };
    yh.prototype.Uh = function(a) {
        var b = hh(this, a);
        z(b, Y(this, "active")) ? (this.ea(a), x(b, Y(this, "active"))) : (this.show(a), w(b, Y(this, "active")))
    };
    yh.prototype.Th = function(a) {
        (a = Dd(a, Y(this, "card"))) && this.ea(a.cardTargetNode)
    };
    var zh = function() {
        this.K = {}
    };
    t(zh, gh);
    aa(zh);
    zh.prototype.P = "expander";
    zh.prototype.T = function() {
        this.addBehavior("click", this.Ja, "head");
        this.addBehavior("keypress", this.sd, "head")
    };
    zh.prototype.Ja = function(a) {
        Ah(this, a)
    };
    zh.prototype.sd = function(a, b, c) {
        c && 13 == c.keyCode && Ah(this, a)
    };
    var Ah = function(a, b) {
        var c = hh(a, b);
        c && (pb(c, Y(a, "collapsed")), a.pa(c, "expander-action"))
    };
    zh.prototype.collapse = function(a) {
        if (a = hh(this, a)) w(a, Y(this, "collapsed")), this.pa(a, "expander-action")
    };
    zh.prototype.expand = function(a) {
        if (a = hh(this, a)) x(a, Y(this, "collapsed")), this.pa(a, "expander-action")
    };
    zh.prototype.Fa = function(a) {
        return I(Y(this, "body"), a)
    };
    var Bh = function() {
        this.K = {}
    };
    t(Bh, gh);
    aa(Bh);
    n = Bh.prototype;
    n.P = "form-input";
    n.T = function() {
        B && !D(9) && (this.addBehavior("click", this.Ve, "checkbox"), this.addBehavior("keypressed", this.Ve, "checkbox"), this.addBehavior("click", this.We, "radio"), this.addBehavior("keypressed", this.We, "radio"));
        this.addBehavior("keyup", this.wd, "text");
        this.addBehavior("keyup", this.wd, "textarea");
        this.addBehavior("keyup", this.wd, "bidi")
    };
    n.Ve = function(a) {
        var b = K(a, l, Y(this, "checkbox-container"));
        A(b, "checked", a.checked)
    };
    n.We = function() {
        var a = jc(Y(this, "radio")),
            b = Y(this, "radio-container");
        u(a, function(a) {
            var d = K(a, l, b);
            d && A(d, "checked", a.checked)
        })
    };
    n.wd = function(a) {
        var b = a.value,
            c = "";
        fd(b) ? c = "rtl" : dd.test(b) && (c = "ltr");
        a.dir = c
    };
    var Ch = function() {
        this.K = {}
    };
    t(Ch, uh);
    aa(Ch);
    n = Ch.prototype;
    n.P = "hovercard";
    n.T = function() {
        this.addBehavior("mouseover", this.Nh, "target", 5, j);
        this.addBehavior("mouseout", this.Lh, "target", 5, j);
        this.addBehavior("mouseover", this.Oh, "card", 100);
        this.addBehavior("mouseout", this.Mh, "card", 100)
    };
    n.Nh = function(a) {
        if (Dh != a) {
            Dh && (this.ea(Dh), Dh = l);
            var b = r(this.show, this, a),
                c = parseInt(this.getData(a, "delay-show"), 10),
                b = M(b, -1 < c ? c : this.Qe.Fh);
            this.setData(a, "card-timer", b.toString());
            Dh = a;
            a.alt && (this.setData(a, "card-alt", a.alt), a.alt = "");
            a.title && (this.setData(a, "card-title", a.title), a.title = "")
        }
    };
    n.Lh = function(a) {
        var b = parseInt(this.getData(a, "card-timer"), 10);
        pd(b);
        hh(this, a).isCardHidable = j;
        b = parseInt(this.getData(a, "delay-hide"), 10);
        b = -1 < b ? b : this.Qe.Ph;
        M(r(this.Rh, this, a), b);
        if (b = this.getData(a, "card-alt")) a.alt = b;
        if (b = this.getData(a, "card-title")) a.title = b
    };
    n.Rh = function(a) {
        hh(this, a).isCardHidable && (this.ea(a), Dh = l)
    };
    n.Oh = function(a) {
        a && (a.cardRootNode.isCardHidable = m)
    };
    n.Mh = function(a, b, c) {
        a && (b = a.cardTargetNode, Cc(a, c.relatedTarget) || this.ea(b))
    };
    var Dh = l;
    var Eh = function() {
        this.K = {}
    };
    t(Eh, gh);
    aa(Eh);
    n = Eh.prototype;
    n.P = "overlay";
    n.T = function() {
        this.addBehavior("click", this.Ye, "target");
        this.addBehavior("click", this.ec, "close")
    };
    n.Ye = function(a) {
        if (a = hh(this, a)) {
            var b = Y(this, "fg"),
                c = F(b);
            if (!c) {
                var d = this.mb(a);
                if (d) {
                    c = document.createElement("div");
                    c.id = b;
                    c.className = b;
                    b = this.getData(a, "overlay-class") || "";
                    w(c, b);
                    var e = document.createElement("div");
                    e.className = Y(this, "fg-content");
                    var b = document.createElement("div"),
                        f = Y(this, "base");
                    b.id = f;
                    w(b, f);
                    var g = Y(this, "bg"),
                        f = document.createElement("div");
                    f.id = g;
                    f.className = g;
                    f.style.height = oc() + "px";
                    g = document.createElement("span");
                    w(g, Y(this, "align"));
                    b.appendChild(g);
                    e.innerHTML =
                        d.innerHTML;
                    d = H("iframe", l, e);
                    u(d, function(a) {
                        var b = this.getData(a, "onload");
                        b && ((b = q(b)) && Q(a, "load", b), a.src = this.getData(a, "src") || a.src)
                    }, this);
                    c.appendChild(e);
                    d = document.getElementsByTagName("embed");
                    e = document.getElementsByTagName("object");
                    g = r(function(a) {
                        var b = a.style.visibility;
                        "hidden" != b && (this.setData(a, "overlay-hidden", "true"), b && this.setData(a, "overlay-visibility-value", b), a.style.visibility = "hidden")
                    }, this);
                    u(d, g);
                    u(e, g);
                    b.appendChild(c);
                    document.body.appendChild(f);
                    document.body.appendChild(b);
                    this.getData(a, "disable-shortcuts") || (c = r(function(a) {
                        z(a.target, Y(this, "base")) && this.ec()
                    }, this), Q(b, "click", c), Q(document, "keydown", r(this.cc, this)));
                    this.pa(a, "overlay-shown")
                }
            }
        }
    };
    n.ec = function() {
        var a = Y(this, "bg"),
            b = F(Y(this, "fg"));
        if (b) {
            if (z(b, Y(this, "unclosable"))) return;
            V(b);
            document.body.removeChild(b.parentNode)
        }(a = F(a)) && document.body.removeChild(a);
        var a = document.getElementsByTagName("embed"),
            b = document.getElementsByTagName("object"),
            c = r(function(a) {
                if (this.getData(a, "overlay-hidden")) {
                    vd(a, "overlay-hidden");
                    var b = this.getData(a, "overlay-visibility-value");
                    a.style.visibility = b ? b : l
                }
            }, this);
        u(a, c);
        u(b, c);
        Rd(document, "keydown", r(this.cc, this))
    };
    n.cc = function(a) {
        27 == a.keyCode && this.ec()
    };
    n.mb = function(a) {
        return I(Y(this, "content"), a)
    };
    n.ea = function() {
        this.ec()
    };
    n.show = function(a) {
        this.Ye(a)
    };
    var Fh = function() {
        this.K = {}
    };
    t(Fh, gh);
    aa(Fh);
    Fh.prototype.P = "redirect-link";
    Fh.prototype.T = function() {
        this.addBehavior("click", this.Ja)
    };
    Fh.prototype.Ja = function(a) {
        if (!P(a, "redirect-href-updated")) {
            O(a, "redirect-href-updated", "true");
            var b = L("XSRF_REDIRECT_TOKEN"),
                c = L("XSRF_FIELD_NAME");
            if (b && c) {
                var d = {};
                d.q = a.href;
                d[c] = b;
                a.href = re("/redirect", d)
            }
        }
    };
    var Gh = function() {
        this.K = {}
    };
    t(Gh, gh);
    aa(Gh);
    n = Gh.prototype;
    n.P = "slider";
    n.T = function() {
        this.addBehavior("click", this.Ih, "num");
        this.addBehavior("click", this.Jh, "prev");
        this.addBehavior("click", this.Hh, "next");
        this.addBehavior("mouseover", this.fc, "ajax-trigger")
    };
    n.Ih = function(a) {
        if (a) {
            var b = hh(this, a),
                a = parseInt(this.getData(a, "slider-num"), 10);
            if (isNaN(a) || 0 > a) a = 0;
            this.hc(b, a)
        }
    };
    n.Hh = function(a, b, c) {
        if (a) {
            a = hh(this, a);
            if (z(a, Y(this, "fluid"))) {
                if (a) {
                    var d = this.Fa(a),
                        e = Hh(this, Ih(this, d)),
                        f = 0 < e.length,
                        b = parseInt(this.getData(a, "scroll-offset"), 10);
                    if (isNaN(b) || 0 < b) b = 0;
                    var g = d.offsetWidth;
                    f ? (d = Jh(e), g = Math.floor(g / d), b = Math.abs(Math.floor(b / d)) - 1, Kh(this, a, (0 <= b ? b : 0) + g)) : (this.td && 8 > document.documentMode ? Math.abs(b - g) < d.scrollWidth && (b -= g) : d.offsetWidth < d.scrollWidth && (b -= g), Lh(this, a, b))
                }
            } else if (a) {
                b = parseInt(this.getData(a, "slider-current"), 10);
                if (isNaN(b) || 0 > b) b = 0;
                g = parseInt(this.getData(a,
                    "slider-slides"), 10);
                if (isNaN(g) || 0 > g) g = 0;
                b = Math.min(b + 1, g - 1);
                this.hc(a, b)
            }
            c.preventDefault()
        }
    };
    n.Jh = function(a, b, c) {
        if (a) {
            a = hh(this, a);
            if (z(a, Y(this, "fluid"))) {
                if (a) {
                    var d = this.Fa(a),
                        e = Hh(this, Ih(this, d)),
                        f = 0 < e.length,
                        b = parseInt(this.getData(a, "scroll-offset"), 10);
                    if (isNaN(b) || 0 < b) b = 0;
                    d = d.offsetWidth;
                    f ? (e = Jh(e), f = Math.floor(d / e), b = Math.abs(Math.floor(b / e)) - 1, Kh(this, a, (0 <= b ? b : 0) - f)) : (b += d, 0 < b && (b = 0), Lh(this, a, b))
                }
            } else if (a) {
                b = parseInt(this.getData(a, "slider-current"), 10);
                if (isNaN(b) || 0 > b) b = 0;
                b = Math.max(b - 1, 0);
                this.hc(a, b)
            }
            c.preventDefault()
        }
    };
    n.fc = function(a) {
        if (a = hh(this, a)) {
            var b = Y(this, "ajax-trigger"),
                c = jc(b, a);
            u(c, function(a) {
                x(a, b)
            });
            Mh(this, a)
        }
    };
    var Mh = function(a, b, c) {
        var d = a.getData(b, "slider-ajax-url");
        d && !a.getData(b, "slider-loaded") && (a.setData(b, "slider-loaded", "true"), S(d, {
            j: a,
            f: function(a, d) {
                var g = vc(d.slides_html),
                    k = I(Y(this, "slides"), b);
                k && g && wc(k, g);
                c && c()
            }
        }))
    };
    Gh.prototype.hc = function(a, b) {
        a && (this.getData(a, "slider-ajax-url") && !this.getData(a, "slider-loaded") ? Mh(this, a, r(this.hc, this, a, b)) : Nh(this, a, b))
    };
    var Nh = function(a, b, c) {
            var d = H(l, Y(a, "num"), b),
                e = Y(a, "num-current"),
                f;
            u(d, function(a) {
                f = this.getData(a, "slider-num") == c;
                A(a, e, f);
                A(a, "yt-uix-pager-selected", f);
                z(a, "yt-uix-button") && A(a, "yt-uix-button-toggled", f)
            }, a);
            if (d = I(Y(a, "slides"), b)) {
                var g = Ih(a, d);
                if (g) {
                    var k = -1 * c * g.offsetWidth + "px";
                    Ag(g) ? d.style.right = k : d.style.left = k
                }
            }
            if (d = I("yt-uix-pager-current-page", b)) d.innerHTML = c + 1;
            a.setData(b, "slider-current", c + "");
            Oh(a, b)
        },
        Oh = function(a, b) {
            if (b) {
                var c = parseInt(a.getData(b, "slider-current"), 10),
                    d =
                    parseInt(a.getData(b, "slider-slides"), 10),
                    e = jc(Y(a, "next"), b),
                    f = jc(Y(a, "prev"), b);
                u(e, function(a) {
                    a.disabled = m
                });
                u(f, function(a) {
                    a.disabled = m
                });
                0 == c && u(f, function(a) {
                    a.disabled = j
                });
                c == d - 1 && u(e, function(a) {
                    a.disabled = j
                })
            }
        },
        Kh = function(a, b, c) {
            if (b) {
                var d = a.Fa(b),
                    e = Hh(a, Ih(a, d));
                if (!(0 >= e.length)) {
                    c >= e.length && (c = e.length - 1);
                    var f = parseInt(a.getData(b, "scroll-offset"), 10);
                    if (isNaN(f) || 0 < f) f = 0;
                    var d = d.offsetWidth,
                        g = Jh(e),
                        f = Math.abs(Math.floor(f / g)) - 1;
                    if (c > (0 <= f ? f : 0)) {
                        var f = Math.floor(d / g),
                            k = e.length;
                        c + f > k && (c = k - f + 1)
                    }
                    0 > c && (c = 0);
                    c = e[c];
                    f = Ag(b) ? c.offsetLeft - d + g + 10 : -1 * (c.offsetLeft - 10);
                    Lh(a, b, f)
                }
            }
        },
        Lh = function(a, b, c) {
            if (b) {
                isNaN(c) && (c = 0);
                var d = a.Fa(b),
                    e = Ih(a, d),
                    f = H(l, Y(a, "title"), e),
                    g = z(b, Y(a, "scroll")),
                    k = Ag(b);
                if (g) d.scrollLeft = k ? Nb ? c : d.scrollWidth - d.offsetWidth + c : -c;
                else {
                    var o = k ? "right" : "left";
                    e.style[o] = c + "px";
                    u(f, function(a) {
                        a.style[o] = -1 * c + "px"
                    })
                }
                a.setData(b, "scroll-offset", c + "")
            }
        };
    Gh.prototype.Fa = function(a) {
        return I(Y(this, "body"), a)
    };
    var Ih = function(a, b) {
            return I(Y(a, "slide"), b)
        },
        Hh = function(a, b) {
            return H(l, Y(a, "slide-unit"), b)
        },
        Jh = function(a) {
            if (0 == a.length) return 0;
            var b = a[0],
                c = b.offsetWidth;
            1 < a.length && (a = a[1], c = Ag(b) ? b.offsetLeft - a.offsetLeft : a.offsetLeft - b.offsetLeft);
            return c
        },
        Ph = function(a, b) {
            var c = a.Fa(b),
                d = Hh(a, Ih(a, c));
            if (!d.length) return [];
            var e = parseInt(a.getData(b, "scroll-offset"), 10);
            if (isNaN(e) || 0 < e) e = 0;
            var f = c.offsetWidth,
                g = Jh(d),
                c = Math.floor(-e / g),
                c = Math.max(0, c),
                e = Math.ceil((-e + f) / g),
                e = Math.min(e, d.length);
            return gb(eb(d),
                c, e)
        };
    var Qh = function(a, b, c) {
        b || (b = {});
        var d = c || window,
            c = "undefined" != typeof a.href ? a.href : "" + a,
            a = b.target || a.target,
            e = [],
            f;
        for (f in b) switch (f) {
            case "width":
            case "height":
            case "top":
            case "left":
                e.push(f + "=" + b[f]);
                break;
            case "target":
            case "noreferrer":
                break;
            default:
                e.push(f + "=" + (b[f] ? 1 : 0))
        }
        f = e.join(",");
        if (b.noreferrer) {
            if (b = d.open("", a, f)) B && -1 != c.indexOf(";") && (c = "'" + c.replace(/'/g, "%27") + "'"), b.opener = l, Ob ? b.location.href = c : (c = za(c), b.document.write('<META HTTP-EQUIV="refresh" content="0; url=' + c + '">'),
                b.document.close())
        } else b = d.open(c, a, f);
        return b
    };
    var Rh = function(a, b, c) {
            window.location = re(a, b || {}) + (c || "")
        },
        Sh = function(a, b) {
            var c = b || {};
            c.target = c.target || a.target || "YouTube";
            c.width = c.width || 600;
            c.height = c.height || 600;
            c = Qh(a, c);
            if (!c) return j;
            c.opener || (c.opener = window);
            c.focus();
            return m
        };
    var Th = function() {
        this.K = {}
    };
    t(Th, gh);
    aa(Th);
    Th.prototype.P = "tile";
    Th.prototype.T = function() {
        this.addBehavior("click", this.Ja)
    };
    Th.prototype.Ja = function(a, b, c) {
        !K(c.target, "a") && !K(c.target, "button") && (a = I(Y(this, "link"), a)) && (B && !D(9) ? a.click() : Rh(a.href))
    };
    var Uh = function() {
        this.K = {}
    };
    t(Uh, gh);
    aa(Uh);
    n = Uh.prototype;
    n.P = "tooltip";
    n.kc = 0;
    n.T = function() {
        this.addBehavior("mouseover", this.fc);
        this.addBehavior("mouseout", this.qd);
        this.addBehavior("click", this.qd);
        this.addBehavior("touchstart", this.Gh);
        this.addBehavior("touchend", this.Se);
        this.addBehavior("touchcancel", this.Se)
    };
    n.ud = function() {
        return !(this.td && 0 == $b.indexOf("6"))
    };
    n.fc = function(a) {
        if (!(this.kc && 1E3 > oa() - this.kc)) {
            var b = parseInt(this.getData(a, "tooltip-hide-timer"), 10);
            b && (vd(a, "tooltip-hide-timer"), pd(b));
            var b = r(function() {
                    Vh(this, a);
                    vd(a, "tooltip-show-timer")
                }, this),
                c = parseInt(this.getData(a, "tooltip-show-delay"), 10) || 0,
                b = M(b, c);
            this.setData(a, "tooltip-show-timer", b.toString());
            a.title && (this.setData(a, "tooltip-text", a.title), a.title = "")
        }
    };
    n.qd = function(a) {
        var b = parseInt(this.getData(a, "tooltip-show-timer"), 10);
        b && (pd(b), vd(a, "tooltip-show-timer"));
        b = r(function() {
            Wh(this, a);
            vd(a, "tooltip-hide-timer")
        }, this);
        b = M(b, 50);
        this.setData(a, "tooltip-hide-timer", b.toString());
        if (b = this.getData(a, "tooltip-text")) a.title = b
    };
    n.Gh = function(a, b, c) {
        this.kc = 0;
        this.fc(ch(Y(this), c.changedTouches[0].target, bh(b, Y(this))), b)
    };
    n.Se = function(a, b, c) {
        this.kc = oa();
        this.qd(ch(Y(this), c.changedTouches[0].target, bh(b, Y(this))))
    };
    var Xh = function(a, b, c, d) {
        a.setData(b, "tooltip-text", c);
        var e = a.getData(b, "content-id");
        if (e = F(e)) e.innerHTML = c, d && Wh(a, b)
    };
    Uh.prototype.Ub = function(a) {
        return this.getData(a, "tooltip-text") || a.title
    };
    var Vh = function(a, b) {
            if (b) {
                var c = a.Ub(b);
                if (c) {
                    var d = F(Yh(a, b));
                    if (!d) {
                        d = document.createElement("div");
                        d.id = Yh(a, b);
                        d.className = Y(a, "tip");
                        var e = document.createElement("div");
                        e.className = Y(a, "tip-body");
                        var f = document.createElement("div");
                        f.className = Y(a, "tip-arrow");
                        var g = document.createElement("div");
                        g.className = Y(a, "tip-content");
                        var k = a.zb(b),
                            o = Yh(a, b, "content");
                        g.id = o;
                        a.setData(b, "content-id", o);
                        e.appendChild(g);
                        k && d.appendChild(k);
                        d.appendChild(e);
                        d.appendChild(f);
                        document.body.appendChild(d);
                        Xh(a, b, c);
                        c = z(b, Y(a, "reverse"));
                        Zh(a, b, d, e, k, f, 5, c) & 2 && Zh(a, b, d, e, k, f, 1, !c);
                        var v = Y(a, "tip-visible");
                        M(function() {
                            w(d, v)
                        }, 0)
                    }
                }
            }
        },
        Zh = function(a, b, c, d, e, f, g, k) {
            A(c, Y(a, "tip-reverse"), k);
            var o = 0,
                v = 1;
            k && (o = 1, v = 0);
            k = Eg(d);
            d.style.left = "3px";
            e && (e.style.left = "3px", e.style.height = k.height + "px", e.style.width = k.width + "px");
            k.width += 6;
            d = b.offsetWidth / 2;
            g = Zg(b, o, c, v, new qb(d - k.width / 2, 0), l, g, k);
            e = Fg(c);
            o = Fg(b);
            v = o.left - e.left + d - 5;
            k = k.width - 10 - 6;
            a = a.getData(b, "force-tooltip-direction");
            v = "left" == a ? k : "right" ==
                a ? 6 : Math.max(Math.min(v, k), 6);
            f.style.left = v + "px";
            f = e.left + v + 5;
            a = o.left;
            b = o.left + o.width;
            f > b ? c.style.left = b - v + "px" : f < a && (c.style.left = a - v + "px");
            return g
        },
        Wh = function(a, b) {
            if (b) {
                var c = F(Yh(a, b));
                c && ($h(c), document.body.removeChild(c), vd(b, "content-id"))
            }
        },
        Yh = function(a, b, c) {
            a = Y(a) + yd(b);
            c && (a += "-" + c);
            return a
        };
    Uh.prototype.zb = function(a) {
        var b = l;
        Hb && z(a, Y(this, "masked")) && ((b = F("yt-uix-tooltip-shared-mask")) ? (b.parentNode.removeChild(b), U(b)) : (b = document.createElement("iframe"), b.src = 'javascript:""', b.id = "yt-uix-tooltip-shared-mask", b.className = Y(this, "tip-mask")));
        return b
    };
    var $h = function(a) {
        var b = F("yt-uix-tooltip-shared-mask"),
            c = b && Kc(b, function(b) {
                return b == a
            }, m, 2);
        b && c && (b.parentNode.removeChild(b), V(b), document.body.appendChild(b))
    };
    var ai = {
        qj: 0,
        ui: 1,
        Qd: 2,
        Wi: 3,
        vi: 4,
        Oj: 5,
        Qj: 6,
        Nj: 7,
        Lj: 8,
        Mj: 9,
        Pj: 10,
        Kj: 11,
        xj: 12,
        wj: 13,
        vj: 14,
        Li: 15,
        hj: 16,
        kj: 17,
        lj: 18,
        jj: 19,
        ij: 20,
        yj: 21,
        yi: 22,
        Jj: 23,
        xi: 24,
        hi: 25,
        zi: 26,
        Ji: 27,
        tj: 28,
        wi: 29,
        sj: 30,
        Fj: 31,
        Ej: 32,
        Gi: 33,
        Cj: 34,
        zj: 35,
        Aj: 36,
        Bj: 37,
        Dj: 38,
        Xi: 39,
        nj: 40,
        ii: 41,
        mj: 42,
        ki: 43,
        Pd: 44,
        Ai: 45,
        ej: 46,
        Gj: 47,
        Rj: 48,
        Sj: 49,
        Uj: 50,
        uj: 51,
        pi: 52,
        ri: 53,
        fj: 54,
        Si: 55,
        $e: 56,
        rj: 57,
        oj: 58,
        Ii: 59,
        bj: 60,
        Ti: 61,
        Yi: 62,
        ji: 63,
        Ij: 64,
        mi: 65,
        li: 66,
        Zi: 67,
        ti: 68,
        Ci: 69,
        Mi: 70,
        cj: 71,
        Ki: 72,
        pj: 73,
        $i: 74,
        Rd: 75,
        uh: 76,
        Je: 77,
        Di: 78,
        Hj: 79,
        Ui: 80,
        qi: 81,
        aj: 82,
        Ni: 83,
        Pi: 84,
        Oi: 85,
        Qi: 86,
        Ri: 87,
        ni: 88,
        gi: 89,
        oi: 90,
        gj: 91,
        dj: 92,
        si: 93,
        Tj: 94,
        Fi: 95,
        Ei: 96,
        Hi: 97,
        Vi: 98,
        Bi: 99
    };
    var bi = 0,
        ci = 0,
        di = 0,
        ei = 0,
        fi = m,
        gi = function() {
            var a = nc(window);
            return pc(document).y + a.height + ci
        },
        ii = function() {
            var a = gi(),
                b = di;
            ei = a;
            var c = Math.abs(b - a);
            if (!b || 400 <= c) {
                c = hi(a);
                for (b = hi(b); b < c;) og("thumb-group-" + c), c--;
                di = a
            }
        },
        ji = function() {
            for (var a = hi(ei); 0 <= a; a--) og("thumb-group-" + a)
        },
        li = function(a) {
            ki(function(a, c) {
                var d = P(a, "group-key");
                d && (ng(a, d), vd(a, "group-key"));
                a.src = c
            }, a)
        },
        mi = function(a, b) {
            fi && ki(function(a, d) {
                var e = P(a, "group-key");
                if (!(P(a, "thumb-manual") || e && !b)) {
                    e && ng(a, e);
                    if (e = F(a)) {
                        var f =
                            0,
                            g = 0;
                        if (e.offsetParent) {
                            do f += e.offsetLeft, g += e.offsetTop; while (e = e.offsetParent)
                        }
                        e = new qb(f, g)
                    } else e = l;
                    e = "thumb-group-" + hi(e.y);
                    mg(a, d, e);
                    O(a, "group-key", e)
                }
            }, a)
        },
        ki = function(a, b) {
            var c = H("img", l, b);
            u(c, function(b) {
                var c = P(b, "thumb");
                c && a.call(p, b, c)
            })
        },
        hi = function(a) {
            return Math.ceil(Math.max(0, a - bi) / 400)
        };
    var ni = function(a, b, c) {
        this.Bh = b;
        this.Dh = c;
        this.Eh = "ad_creative_" + a;
        this.Ch = "ad_creative_expand_btn_" + a;
        this.Ah = "ad_creative_collapse_btn_" + a
    };
    ni.prototype.collapse = function() {
        V(this.Eh);
        mi(F("page"), j);
        ji();
        this.Dh || V(this.Ah);
        U(this.Ch);
        var a = R.getInstance();
        a.set("HIDDEN_MASTHEAD_ID", this.Bh);
        a.save();
        X("homepage_collapse_masthead_ad", i, i)
    };
    ni.prototype.expand = function() {
        var a = R.getInstance();
        a.set("HIDDEN_MASTHEAD_ID", m);
        a.save();
        X("homepage_expand_masthead_ad", i, i);
        Rh(document.location.href)
    };
    var oi = function(a) {
            var b;
            switch (a) {
                case "PENDING":
                    b = N("COMMENT_PENDING");
                    break;
                case "BLOCKED":
                    b = N("COMMENT_BLOCKED");
                    break;
                case "EMAIL":
                    b = N("COMMENT_ERROR_EMAIL");
                    break;
                case "INLINE_CAPTCHAFAIL":
                    b = N("COMMENT_CAPTCHAFAIL")
            }
            return b || l
        },
        pi = function(a, b) {
            this.i = a;
            this.Qa = Ed("button", l, a);
            this.V = Ed("textarea", l, a);
            this.ya = l;
            this.Uc = I("comments-remaining-count", this.i);
            this.pg = parseInt(this.Uc.innerHTML, 10);
            this.Sa = I("comments-post-message", this.i);
            this.Md = I("yt-alert-content", this.Sa);
            Q(this.V, "focus",
                r(this.je, this));
            Q(this.V, "blur", r(this.wg, this));
            Q(this.i, "submit", r(this.Dc, this));
            var c = r(this.xg, this);
            Q(this.V, "change", c);
            Q(this.V, "keyup", c);
            b && this.je()
        };
    pi.prototype.reset = function() {
        qi(this);
        this.V.blur();
        x(this.i, "has-focus");
        this.Qa.disabled = m;
        this.setValue("")
    };
    pi.prototype.focus = function() {
        this.V.focus()
    };
    pi.prototype.setValue = function(a) {
        this.V.value = a;
        var b = this.V,
            a = a.length,
            c;
        try {
            c = "number" == typeof b.selectionStart
        } catch (d) {
            c = m
        }
        c ? (b.selectionStart = a, b.selectionEnd = a) : B && ("textarea" == b.type && (a = b.value.substring(0, a).replace(/(\r\n|\r|\n)/g, "\n").length), b = b.createTextRange(), b.collapse(j), b.move("character", a), b.select());
        ri(this)
    };
    var qi = function(a) {
            a.ya && (a.ya.innerHTML = "")
        },
        ri = function(a) {
            var b = a.pg - a.V.value.length;
            a.Uc.innerHTML = b + "";
            b = 0 > b;
            A(a.Uc, "too-many", b);
            a.Qa.disabled = b
        };
    pi.prototype.xg = function() {
        ri(this)
    };
    pi.prototype.je = function() {
        z(this.i, "has-focus") || V(this.Sa);
        w(this.i, "has-focus");
        Ne("comments-focus", this.i)
    };
    pi.prototype.wg = function() {
        Ne("comments-blur", this.i)
    };
    pi.prototype.Dc = function(a) {
        a.preventDefault();
        if (!this.Qa.disabled) {
            this.Qa.disabled = j;
            a = oe($c(this.i));
            a.screen = ne({
                h: window.screen.height,
                w: window.screen.width,
                d: window.screen.colorDepth
            });
            var b = a.comment,
                b = {
                    return_ajax: "true",
                    len: b.length,
                    wc: b.split(/\s+/).length
                };
            this.i.reply_parent_id.value && (b.reply = 1);
            S(this.i.action, {
                format: "XML",
                method: "POST",
                g: b,
                z: a,
                aa: function(a, b) {
                    var e = b.str_code;
                    switch (e) {
                        case "OK":
                            e = document.createElement("ul");
                            e.innerHTML = b.html_content;
                            e = Bc(e);
                            if (this.i.reply_parent_id.value) {
                                var f =
                                    K(this.i, l, "comments-post-container"),
                                    g = K(f, l, "comment");
                                yc(f);
                                y(g, "replying", "has-child");
                                w(e, "child");
                                A(e, "last", !(g.nextElementSibling != i ? g.nextElementSibling : zc(g.nextSibling)));
                                g.parentNode && g.parentNode.insertBefore(e, g.nextSibling)
                            } else f = K(this.i, l, "comments-section"), f = I("comment-list", f), f.insertBefore(e, f.childNodes[0] || l), this.reset();
                            li(e);
                            break;
                        case "PENDING":
                            qi(this);
                            this.Md.innerHTML = N("COMMENT_PENDING");
                            y(this.Sa, "yt-alert-error", "yt-alert-info");
                            U(this.Sa);
                            break;
                        default:
                            si(this,
                                e)
                    }
                },
                j: this
            })
        }
    };
    var si = function(a, b) {
        a.Md.innerHTML = oi(b) || N("COMMENT_ERROR");
        y(a.Sa, "yt-alert-info", "yt-alert-error");
        U(a.Sa);
        switch (b) {
            case "INLINE_CAPTCHA":
            case "INLINE_CAPTCHAFAIL":
                S("/comment_servlet?gimme_captcha=1", {
                    format: "XML",
                    method: "POST",
                    z: {
                        session_token: T.comment_servlet
                    },
                    f: function(a, b) {
                        if (!this.ya) {
                            this.ya = document.createElement("div");
                            this.ya.className = "comment-captcha";
                            var e = this.V;
                            e.parentNode && e.parentNode.insertBefore(this.ya, e.nextSibling)
                        }
                        this.ya.innerHTML = b.html_content;
                        this.Qa.disabled = m
                    },
                    j: a
                });
                break;
            default:
                a.Qa.disabled = m, qi(a)
        }
    };
    var ti = function(a) {
        this.c = a;
        this.vb = I("watch-more-comments-button");
        this.Jf = !!L("ENABLE_LIVE_COMMENTS");
        this.Oc()
    };
    ti.prototype.Oc = function() {
        var a = I("comments-pagination", this.c);
        P(a, "ajax-enabled") && Qd(this.c, "click", r(this.Sg, this), "yt-uix-pager-button");
        Q(this.vb, "click", r(this.Ug, this));
        Qd(this.c, "click", r(this.Tg, this), "comments-section-pop-out")
    };
    ti.prototype.Sg = function(a) {
        a.preventDefault();
        a = a.currentTarget;
        if (!z(a, "yt-uix-button-toggled")) {
            var b = parseInt(P(a, "page"), 10);
            U("comments-loading");
            Ne("comments-page-changing");
            ui(this, b, function(a) {
                if (a) {
                    this.c.innerHTML = a;
                    li(this.c);
                    var a = I("comment-list", this.c),
                        d = I("live-comments-setting", this.c);
                    a && Ne("comments-page-changed", a, d, b)
                }
                V("comments-loading")
            })
        }
    };
    ti.prototype.Ug = function() {
        V(this.vb);
        U("watch-more-comments", "watch-more-comments-loading");
        var a = parseInt(P(this.vb, "page"), 10);
        ui(this, a, function(b) {
            var c = F("watch-more-comments");
            c.innerHTML += b;
            li(c);
            O(this.vb, "page", a + 1);
            U(this.vb);
            V("watch-more-comments-loading")
        })
    };
    ti.prototype.Tg = function() {
        Sh(L("COMMENTS_POPUP_URL"), {
            width: 350,
            height: 500,
            resizable: j
        })
    };
    var ui = function(a, b, c) {
        O(a.c, "type", "everything");
        S("/watch_ajax?action_get_comments=1", {
            format: "XML",
            g: {
                v: L("VIDEO_ID"),
                p: b,
                commentthreshold: L("COMMENTS_THRESHHOLD"),
                commentfilter: L("COMMENTS_FILTER"),
                commenttype: "everything",
                enable_live_comments: a.Jf ? "yes" : l,
                page_size: L("COMMENTS_PAGE_SIZE"),
                source: L("COMMENT_SOURCE")
            },
            f: function(a, b) {
                c.call(this, b.html_content)
            },
            j: a
        })
    };
    var Ei = function(a) {
            var b = a.currentTarget,
                a = K(b, l, "comment");
            switch (P(b, "action")) {
                case "approve":
                    vi(a);
                    break;
                case "block":
                    confirm(N("BLOCK_USER")) && (wi(a, j), w(a, "blocked"));
                    break;
                case "unblock":
                    wi(a, m);
                    x(a, "blocked");
                    break;
                case "flag":
                    xi(a);
                    break;
                case "unflag":
                    a = P(a, "id");
                    S("/comment_servlet", {
                        format: "XML",
                        method: "POST",
                        z: {
                            unmark_comment_as_spam: a,
                            entity_id: L("VIDEO_ID"),
                            session_token: T.comment_servlet
                        }
                    });
                    break;
                case "hide":
                    w(a, "hidden");
                    break;
                case "show":
                    x(a, "hidden");
                    break;
                case "remove":
                    yi(a);
                    break;
                case "reply":
                    if (zi())
                        if (z(a, "replying")) Ai(a);
                        else {
                            w(a, "replying");
                            Bi(a);
                            var b = I("comments-post", F("comments-view")),
                                b = Bd(b),
                                c = document.createElement("div");
                            c.className = "comments-post-container";
                            a.appendChild(c);
                            c.appendChild(b);
                            b = new pi(b);
                            b.reset();
                            c = P(a, "author");
                            a = P(a, "id");
                            b.setValue("@" + c + " ");
                            b.i.reply_parent_id.value = a;
                            b.focus()
                        } break;
                case "share":
                    Ci(a);
                    break;
                case "close-share":
                    Bi(a);
                    break;
                case "vote-up":
                    Di(a, j);
                    break;
                case "vote-down":
                    Di(a, m)
            }
        },
        zi = function() {
            return L("COMMENTS_SIGNIN_URL") ?
                (Rh(L("COMMENTS_SIGNIN_URL")), m) : !L("COMMENTS_YPC_CAN_POST_OR_REACT_TO_COMMENT") ? m : j
        },
        vi = function(a) {
            var b = P(a, "id"),
                c = L("VIDEO_ID");
            x(a, "pending");
            S("/comment_servlet?field_approve_comment=1", {
                format: "XML",
                method: "POST",
                z: {
                    comment_id: b,
                    entity_id: c,
                    session_token: T.comment_servlet
                },
                we: function() {
                    w(a, "pending")
                }
            })
        },
        wi = function(a, b) {
            var c = {};
            c[(b ? "" : "un") + "block_user"] = 1;
            var d = P(a, "author");
            S("/link_servlet", {
                format: "XML",
                method: "POST",
                g: c,
                z: {
                    session_token: T.link_servlet,
                    friend_username: d
                }
            })
        },
        xi = function(a) {
            if (zi()) {
                var b =
                    P(a, "id"),
                    c = L("VIDEO_ID");
                V(a);
                w(a, "flagged");
                S("/comment_servlet", {
                    format: "XML",
                    method: "POST",
                    g: {
                        mark_comment_as_spam: b,
                        entity_id: c
                    },
                    z: {
                        session_token: T.comment_servlet
                    },
                    we: function() {
                        U(a);
                        x(a, "flagged")
                    }
                })
            }
        },
        yi = function(a) {
            var b = P(a, "id"),
                c = L("VIDEO_ID");
            V(a);
            S("/comment_servlet?remove_comment=1", {
                format: "XML",
                method: "POST",
                z: {
                    comment_id: b,
                    entity_id: c,
                    session_token: T.comment_servlet
                },
                we: function() {
                    U(a)
                }
            })
        },
        Ai = function(a) {
            z(a, "replying") && (x(a, "replying"), a = I("comments-post-container", a), yc(a))
        },
        Ci = function(a) {
            if (!z(a, "sharing")) {
                w(a, "sharing");
                Ai(a);
                var b = Bd(F("comment-share-area")),
                    c = document.createElement("div");
                c.className = "comments-post-container";
                a.appendChild(c);
                c.appendChild(b);
                var d = P(a, "id"),
                    c = L("COMMENT_SHARE_URL"),
                    c = c.replace("_COMMENT_ID_", d);
                I("comment-share-url", b).value = c;
                var a = I("comment-text", a),
                    e = Ha(Jc(Bc(a))),
                    a = jc("icon-comment-share", b);
                u(a, function(a) {
                    var b = a.getAttribute("action"),
                        b = b.replace("_COMMENT_ID_", d),
                        c;
                    c = -1 != b.indexOf("twitter") ? Ea(e, 80) : Ea(e, 150);
                    b = b.replace("_COMMENT_TEXT_",
                        c);
                    Q(a, "click", r(function(a) {
                        eval(a)
                    }, p, b))
                });
                U(b)
            }
        },
        Bi = function(a) {
            z(a, "sharing") && (x(a, "sharing"), a = I("comments-post-container", a), yc(a))
        },
        Di = function(a, b) {
            if (zi() && !P(a, "voted")) {
                var c = P(a, "id"),
                    d = L("VIDEO_ID"),
                    e = P(a, "score"),
                    f = b ? 1 : -1;
                O(a, "voted", f + "");
                b ? y(a, "voted-down", "voted-up") : y(a, "voted-up", "voted-down");
                c = {
                    a: f,
                    id: c,
                    video_id: d,
                    old_vote: e
                };
                (d = P(a, "tag")) && (c.tag = d);
                S("/comment_voting", {
                    format: "XML",
                    method: "POST",
                    g: c,
                    z: {
                        session_token: T.comment_voting
                    }
                })
            }
        };
    var Fi = m,
        Gi = m;
    var Hi = ["FL", "LL", "QL", "SV", "WL"],
        Ii = function() {
            return parseInt(L("SHUFFLE_VALUE"), 10) || 0
        };
    var Ji = function() {
            var a = ae("watch_queue_new");
            return a ? a.split(",") : []
        },
        Ki = function(a) {
            a = gb(a, 0, 100);
            (a = a.join(",")) ? $d("watch_queue_new", a): be("watch_queue_new")
        },
        Li = function(a) {
            var b = Ji();
            fb(b, a);
            Ki(b)
        };
    var Mi = function(a, b) {
        a.length && S("/video_info_ajax", {
            method: "POST",
            g: {
                action_get_videos_data: 1,
                count: a.length
            },
            z: {
                video_ids: a.join(",")
            },
            f: function(a, d) {
                d.data && b(d.data)
            }
        })
    };
    var Ni = function(a) {
        var b = Ii(),
            c = function(a) {
                for (var c = 1, d = [], a = a.split(""); a.length;) {
                    var c = (b + c) % a.length,
                        e = a[c];
                    bb(a, c);
                    d.push(e)
                }
                return d.join("")
            },
            d = [];
        u(a, function(a, b) {
            d.push({
                id: a,
                key: c(a),
                index: b
            })
        });
        kb(d);
        var e = b >> 8;
        Wa(d, function(a) {
            return e == a.index
        });
        a = gb(d, e).concat(gb(d, 0, e));
        return Ua(a, function(a) {
            return {
                id: a.id,
                Xc: a.index
            }
        })
    };
    var Z = function(a, b, c) {
            this.ca = a;
            this.D = b;
            this.ba = this.Gb = m;
            this.yc = new Ke;
            this.A = c || [];
            this.fb = {};
            this.Eb = !c;
            this.eb = "";
            this.C = -1;
            this.hb = 1;
            this.Db = this.da = 0;
            this.bb = this.cb = "";
            this.za = this.La = this.ra = l
        },
        Oi = function(a) {
            a.yc.gc("LIST_UPDATED")
        };
    Z.prototype.isEqual = function(a) {
        return this.ca != a.ca ? m : this.va() == a.va()
    };
    Z.prototype.Tc = function(a) {
        this.eb = a.eb || "";
        this.C = a.R();
        this.da = a.da;
        this.hb = a.hb;
        this.cb = a.cb;
        this.bb = a.bb
    };
    Z.prototype.ia = function() {
        return this.ca
    };
    Z.prototype.va = function() {
        return this.ca + (this.D || "")
    };
    var Qi = function(a, b) {
            return Pi(a, "occurrences", function() {
                var a = {};
                u(this.ka(), function(b) {
                    a[b] = (a[b] || 0) + 1
                });
                return a
            })[b] || 0
        },
        Si = function(a) {
            return a.S() ? Ri(a) : a.ka()
        };
    Z.prototype.bd = function(a) {
        var b = Si(this);
        if (!b.length) return "";
        a = this.R() + a;
        a >= b.length && (a %= b.length);
        return b[a]
    };
    var Ti = function(a, b) {
        var c = Si(a);
        return Ua(b, function(a) {
            return Za(c, function(b) {
                return b == a
            })
        })
    };
    Z.prototype.ka = function() {
        return db(this.A)
    };
    var Ri = function(a) {
            return Pi(a, "shuffled_ids", function() {
                return Ua(Ui(this), function(a) {
                    return a.id
                })
            })
        },
        Ui = function(a) {
            return Pi(a, "shuffled_videos", function() {
                var a = this.ka();
                return Ni(a)
            })
        };
    n = Z.prototype;
    n.getVideoData = function(a) {
        return this.fb[a] || {}
    };
    n.G = function() {
        return this.ka().length
    };
    n.R = function() {
        return this.S() ? Wa(Ui(this), function(a) {
            return a.Xc == this.C
        }, this) : this.C
    };
    n.S = function() {
        return 0 < this.da
    };
    n.ha = function() {
        return 0 <= this.C
    };
    n.gb = function() {
        return !!this.Gb
    };
    n.Cc = function() {
        return !!L("LIST_COPY_ON_EDIT_ENABLED")
    };
    n.Pa = function() {
        return m
    };
    var Vi = function(a) {
            a.ra = l;
            a.La = l
        },
        Xi = function(a, b) {
            var c = a.R() - 1;
            0 > c && (c = a.G() - 1);
            return Wi(a, c, m, b)
        },
        Wi = function(a, b, c, d) {
            var e = Si(a)[b];
            if (!e) return l;
            var f = {
                v: e
            };
            1 < Qi(a, e) && (e = b + 1 + a.Db, a.S() && (e = Ui(a)[b].Xc + 1), f.index = e);
            c && (f.playnext = a.hb);
            a.S() && (f.shuffle = a.da);
            d && (f.feature = d);
            return se(a.eb, f)
        };
    Z.prototype.clear = function() {
        this.af();
        Oi(this)
    };
    Z.prototype.removeItem = function(a) {
        this.S() && (a = Ui(this)[a].Xc);
        var b = this.ka()[a];
        this.ye(a);
        this.C > a ? this.C -= 1 : this.C == a && (this.C = -1);
        Oi(this);
        return b
    };
    Z.prototype.ye = function(a) {
        var b = this.A[a];
        bb(this.A, a);
        S(this.Ob(), {
            format: "XML",
            method: "POST",
            z: {
                video_ids: b,
                session_token: T.addto_ajax || "",
                playlist_id: this.D || "",
                index: a
            },
            f: function(a, b) {
                this.ra = b.html_content
            },
            r: function(a, b) {
                this.La = b.error_message || N("ERROR_OCCURRED")
            },
            aa: function() {
                Oi(this)
            },
            j: this
        })
    };
    Z.prototype.load = function(a) {
        this.Eb && !this.Gb ? Yi(this, a) : a && (Oi(this), a());
        Zi(this)
    };
    var Zi = function(a) {
            var b = Si(a),
                b = Ta(b, function(a) {
                    return !(a in this.fb)
                }, a);
            u(b, function(a) {
                this.fb[a] = {}
            }, a);
            Mi(b, r(function(a) {
                for (var b in a) this.fb[b] = a[b];
                Oi(this)
            }, a))
        },
        Yi = function(a, b, c) {
            a.Gb = j;
            a.Eb = m;
            var d = {
                style: "bottomfeedr_json",
                action_get_list: 1,
                list: a.va()
            };
            c && Ab(d, c);
            S("/list_ajax", {
                g: d,
                f: function(a, b) {
                    var c = b.data;
                    this.A = db(c.videos);
                    this.D = c.list_id;
                    this.ba = !!c.editable;
                    this.eb = c.video_url || "";
                    this.cb = c.menu_title_html;
                    this.bb = c.menu_html;
                    this.Db = c.index_offset;
                    var c = c.video_data,
                        d;
                    for (d in c) this.fb[d] = c[d]
                },
                r: function() {},
                aa: function() {
                    this.Gb = m;
                    Zi(this);
                    Oi(this);
                    b && b()
                },
                j: a
            })
        },
        $i = function(a, b, c) {
            a.za = {};
            try {
                b.call(c)
            } finally {
                a.za = l
            }
        },
        Pi = function(a, b, c) {
            if (a.za && b in a.za) return a.za[b];
            c = c.call(a);
            a.za && (a.za[b] = c);
            return c
        };
    var aj = function(a, b) {
        Z.call(this, "FL", a, b)
    };
    t(aj, Z);
    aj.prototype.Ob = function() {
        return "/addto_ajax?action_delete_from_favorites=1"
    };
    var bj = function(a, b) {
        Z.call(this, "LL", a, b)
    };
    t(bj, Z);
    bj.prototype.Ob = function() {
        return "/addto_ajax?action_delete_from_liked=1"
    };
    var cj = function(a, b) {
        Z.call(this, "ML", a, b)
    };
    t(cj, Z);
    cj.prototype.Cc = function() {
        return !!this.ba
    };
    cj.prototype.Pa = function() {
        return !!this.ba
    };
    var dj = function(a, b, c) {
        Z.call(this, a, b, c)
    };
    t(dj, Z);
    n = dj.prototype;
    n.Gc = function(a) {
        fb(this.A, a);
        S("/addto_ajax", {
            format: "XML",
            method: "POST",
            g: {
                action_add_to_playlist: 1
            },
            z: {
                playlist_id: this.D,
                video_ids: a.join(","),
                session_token: T.addto_ajax
            },
            r: function() {
                this.La = N("ERROR_OCCURRED");
                Oi(this)
            },
            j: this
        })
    };
    n.Ob = function() {
        return "/addto_ajax?action_delete_from_playlist=1"
    };
    n.af = function() {
        var a = this.A.length;
        ab(this.A);
        S("/addto_ajax", {
            format: "XML",
            method: "POST",
            g: {
                action_clear_playlist: 1,
                list_length: a,
                type: this.ca
            },
            z: {
                playlist_id: this.D,
                session_token: T.addto_ajax
            },
            r: function() {
                this.La = N("ERROR_OCCURRED");
                Oi(this)
            },
            j: this
        })
    };
    n.fe = function(a, b) {
        var c = this.A[a];
        bb(this.A, a);
        hb(this.A, b, 0, c);
        var d = db(this.A);
        S("/addto_ajax?action_move_playlist_video=1", {
            format: "XML",
            method: "POST",
            z: {
                video_ids: c,
                playlist_id: this.D,
                source_index: a,
                target_index: b,
                session_token: T.addto_ajax
            },
            r: function() {
                var e;
                a: if (e = this.A, !ea(e) || !ea(d) || e.length != d.length) e = m;
                    else {
                        for (var f = e.length, g = 0; g < f; g++)
                            if (e[g] !== d[g]) {
                                e = m;
                                break a
                            } e = j
                    } e && (bb(this.A, b), hb(this.A, a, 0, c));
                this.La = N("ERROR_OCCURRED");
                Oi(this)
            },
            j: this
        })
    };
    n.Pa = function() {
        return !!this.ba
    };
    var ej = function(a) {
        Z.call(this, "QL", l);
        this.ba = j;
        this.Eb = !a
    };
    t(ej, Z);
    n = ej.prototype;
    n.Tc = function(a) {
        ej.Be.Tc.call(this, a);
        this.Eb = m
    };
    n.ka = function() {
        return Ji()
    };
    n.Gc = function(a) {
        Li(a)
    };
    n.ye = function(a) {
        var b = Si(this)[a],
            a = Ji(),
            b = Sa(a, b);
        0 <= b && (bb(a, b), Ki(a))
    };
    n.af = function() {
        Ki([])
    };
    n.fe = function(a, b) {
        var c = Ji(),
            d = c[a];
        bb(c, a);
        hb(c, b, 0, d);
        Ki(c)
    };
    n.Pa = function() {
        return !!this.ba
    };
    var fj = function(a, b) {
        Z.call(this, "SV", a, b);
        this.ba = j
    };
    t(fj, Z);
    fj.prototype.load = function(a) {
        fj.Be.load.call(this, a);
        !this.gb() && this.ha() && (a = Si(this), this.R() >= a.length - 1 && Yi(this, l, {
            load_more: "1"
        }))
    };
    fj.prototype.Ob = function() {
        return "/addto_ajax?action_delete_from_station=1"
    };
    var gj = function(a, b, c) {
        var d = l;
        switch (a) {
            case "PL":
            case "SP":
            case "BP":
            case "WL":
                d = new dj(a, b, c);
                break;
            case "FL":
                d = new aj(b, c);
                break;
            case "LL":
                d = new bj(b, c);
                break;
            case "AV":
            case "BB":
            case "ML":
            case "MC":
                d = new cj(b, c);
                break;
            case "QL":
                d = new ej;
                break;
            case "SV":
                d = new fj(b, c);
                break;
            default:
                d = new Z(a, b, c)
        }
        return d
    };
    !B || cc(9);
    !B || cc(9);
    B && D("8");
    !Ob || D("528");
    Nb && D("1.9b") || B && D("8") || Mb && D("9.5") || Ob && D("528");
    !Nb || D("8");
    B || Nb && D("1.9.3");
    new Ke;
    var ij = function(a, b) {
            this.Ze = a;
            var c = b || l;
            c || (c = hj(this.Ze));
            c = qa("__%s__", "(" + c.join("|") + ")");
            this.Sh = RegExp(c, "g")
        },
        jj = /__([a-z]+(?:_[a-z]+)*)__/g,
        kj = function(a, b) {
            var c = F(a).innerHTML,
                c = c.replace(/^\s*(<\!--\s*)?/, ""),
                c = c.replace(/(\s*--\>)?\s*$/, "");
            return new ij(c, b)
        },
        hj = function(a) {
            var b = [],
                c = {};
            a.replace(jj, function(a, e) {
                e in c || (c[e] = j, b.push(e))
            });
            return b
        };
    ij.prototype.jb = function(a, b, c) {
        return this.Ze.replace(this.Sh, r(function(d, e) {
            b && (e = b(e));
            return c ? a[e] || "" : za(a[e] || "")
        }, this))
    };
    var mj = function(a, b) {
            this.M = !!b;
            this.k = a;
            this.Da = this.M ? F("watch-tray-playlist-slider") : F("playlist-bar-tray");
            this.Q = Ed("ol", l, this.Da);
            this.Ag = F("playlist-bar-title");
            this.zg = F("playlist-bar-extras-menu");
            var c = vb(lj),
                d = F("playlist-bar-template");
            this.M && (d = F("watch-tray-playlist-item-template"));
            this.Hg = kj(d, c);
            d = P(d, "video-thumb-url") || "";
            this.Ig = new ij(d, c);
            Qd(this.Da, "click", r(function() {
                M(r(this.ne, this), 0)
            }, this), "playlist-bar-tray-button")
        },
        lj = {
            classes: "classes",
            list_position: "index",
            video_encrypted_id: "id",
            video_title: "title",
            video_url: "url",
            video_username: "username",
            video_thumb_url: "thumb_url"
        };
    mj.prototype.jb = function(a, b, c, d, e) {
        var f = c.cb;
        f && (this.Ag.innerHTML = f, this.zg.innerHTML = c.bb);
        var g = [];
        u(a, function(a, d) {
            var f = d == b,
                C = c.getVideoData(a);
            C.id = a;
            C.url = Wi(c, d);
            g.push(nj(this, C, d + 1 + e, f))
        }, this);
        this.Q.innerHTML = g.join("");
        d ? this.scroll(3) : this.M || this.scroll(5);
        d = jc("item-count", this.k);
        u(d, function(b) {
            b.innerHTML = a.length
        })
    };
    var nj = function(a, b, c, d) {
        var e = function(a) {
            return lj[a]
        };
        b.index = c;
        b.thumb_url = a.Ig.jb(b, e);
        c = [];
        b.title || c.push("loading");
        d && c.push("playlist-bar-item-playing");
        b.classes = c.join(" ");
        return a.Hg.jb(b, e)
    };
    mj.prototype.ne = function() {
        var a = Ph(Gh.getInstance(), this.Da);
        u(a, function(a) {
            li(a)
        })
    };
    mj.prototype.scroll = function(a, b) {
        var c = Gh.getInstance();
        switch (a) {
            case 3:
                var d = Ed("li", "playlist-bar-item-playing", this.Q);
                if (d) {
                    var e = this.Da;
                    if (e) {
                        var f = Hh(c, Ih(c, c.Fa(e))),
                            d = Sa(f, d);
                        0 <= d && Kh(c, e, d)
                    }
                } else Kh(c, this.Da, 0);
                break;
            case 4:
                b && Kh(c, this.Da, b);
                break;
            case 5:
                e = this.Da, d = c.getData(e, "scroll-offset"), Lh(c, e, parseInt(d, 10) || 0)
        }
        this.ne()
    };
    mj.prototype.Qb = function(a) {
        var b = 0,
            c = r(function() {
                var d = H("li", "playlist-bar-item", this.Q),
                    e = !(b % 2);
                u(a, function(a) {
                    A(d[a], "playlist-bar-item-highlight", e)
                });
                b++;
                6 > b && M(c, 150)
            }, this);
        c()
    };
    var $ = function(a, b, c) {
            this.k = a;
            this.M = !!c;
            this.Ma = new mj(this.k, this.M);
            this.ib = b;
            this.qc = this.Hd = m;
            this.tf = F("playlist-bar-bar");
            Q(this.tf, "click", r(this.vf, this));
            this.Ff = F("playlist-bar-play-button");
            Q(this.Ff, "click", r(this.Bf, this));
            this.M ? (this.Od = F("watch-prev"), this.vc = F("watch-next")) : (this.Od = F("playlist-bar-prev-button"), this.vc = F("playlist-bar-next-button"));
            Q(this.Od, "click", r(this.Cf, this));
            Q(this.vc, "click", r(this.zf, this));
            this.uc = F("playlist-bar-autoplay-button");
            Q(this.uc, "click",
                r(this.uf, this));
            this.Ed = F("playlist-bar-shuffle-button");
            Q(this.Ed, "click", r(this.Df, this));
            this.Fd = F("playlist-bar-toggle-button");
            Q(this.Fd, "click", r(this.Ef, this));
            this.tc = F("playlist-bar-options-menu");
            Qd(this.tc, "click", r(this.Af, this), "yt-uix-button-menu-item");
            this.Q = F("playlist-bar-tray-content");
            this.M && (this.Q = F("watch-tray-playlist-content"));
            Qd(this.Q, "click", r(this.wf, this), "delete");
            Qd(this.Q, "click", r(this.xf, this), "load-lists");
            Qd(this.Q, "click", r(this.yf, this), "load-more");
            this.hf =
                kj(F("playlist-bar-next-up-template"), ["video_encrypted_id"]);
            oj(this, b);
            this.b.G() ? this.show() : this.ea();
            Hb && !this.M && (a = document.createElement("iframe"), a.id = "playlist-bar-mask", a.frameBorder = "0", a.src = 'javascript:""', this.k.insertBefore(a, this.k.firstChild))
        },
        oj = function(a, b, c, d) {
            if (a.b && (a.b.yc.clear("LIST_UPDATED"), a.ib.isEqual(b) || d)) b.Tc(a.ib), a.ib = b;
            a.b = b;
            a.b.yc.Vb("LIST_UPDATED", a.lb, a);
            a.b.load(c);
            a.lb(j)
        };
    $.prototype.ia = function() {
        return this.b.ia()
    };
    $.prototype.va = function() {
        return this.b.va()
    };
    $.prototype.ha = function() {
        return this.b.ha()
    };
    var pj = function(a) {
            return a.ha() && z(a.k, "autoplay-on")
        },
        qj = function(a, b) {
            b ? y(a.k, "autoplay-off", "autoplay-on") : y(a.k, "autoplay-on", "autoplay-off");
            var c = R.getInstance();
            he(ai.uh, !b);
            c.save();
            md("LIST_AUTO_PLAY_ON", b)
        };
    $.prototype.S = function() {
        return z(this.k, "shuffle-on")
    };
    $.prototype.gb = function() {
        return z(this.k, "loading")
    };
    $.prototype.show = function() {
        ge(R.getInstance(), ai.Je) ? rj(this) : sj(this)
    };
    $.prototype.ea = function() {
        y(this.k, ["min", "max"], "hid")
    };
    var rj = function(a) {
            y(a.k, ["hid", "max"], "min");
            tj(a, j)
        },
        sj = function(a) {
            y(a.k, ["hid", "min"], "max");
            tj(a, j)
        };
    n = $.prototype;
    n.toggle = function() {
        var a;
        z(this.k, "min") ? (sj(this), a = m) : (rj(this), a = j);
        X("bf", "toggleBar=1&collapsed=" + a, i);
        var b = R.getInstance();
        he(ai.Je, a);
        b.save();
        uj(this, j)
    };
    n.vf = function(a) {
        Kc(a.target, function(a) {
            a = a.tagName && a.tagName.toLowerCase();
            return "a" == a || "button" == a
        }, j) || this.toggle()
    };
    n.Af = function(a) {
        a.stopPropagation();
        switch (P(a.currentTarget, "action")) {
            case "clear":
                this.clear();
                break;
            case "load-lists":
                vj(this);
                break;
            case "show-active":
                this.ib && oj(this, this.ib);
                break;
            case "save":
                wj(this)
        }
    };
    n.Ef = function() {
        this.toggle()
    };
    n.uf = function() {
        qj(this, !pj(this));
        tj(this)
    };
    n.Df = function() {
        var a = !this.S(),
            b = 0;
        md("SHUFFLE_ENABLED", a);
        a && md("SHUFFLE_VALUE", Math.max(1, 1E6 * Math.random()));
        a ? (y(this.k, "shuffle-off", "shuffle-on"), b = Ii()) : y(this.k, "shuffle-on", "shuffle-off");
        this.b.da = b;
        this.lb();
        this.Ma.scroll(3)
    };
    n.Bf = function() {
        this.next(j, "bf_play")
    };
    n.Cf = function() {
        var a = Xi(this.b, "bf_prev");
        a && Rh(a)
    };
    n.zf = function() {
        this.next(j, "bf_next")
    };
    n.wf = function(a) {
        a.preventDefault();
        a.stopPropagation();
        var b = this.M ? "watch-tray-playlist-item" : "playlist-bar-item",
            c = H("li", b, this.Q),
            a = K(a.currentTarget, "li", b),
            c = Sa(c, a);
        this.b.Cc() && (a = this.b.ia(), xj(this), X("bf", "copyFrom=1&action=delete&list_type=" + a, i));
        var d = this.b.ia(),
            e = Si(this.b)[c];
        this.b.removeItem(c);
        yj(this, N("PLAYLIST_VIDEO_DELETED"), m, m, function() {
            this.b.Gc([e]);
            this.lb();
            var a = this.b.G() - 1;
            this.Ma.scroll(4, a);
            this.Ma.Qb([a]);
            X("bf", "undo_delete=1&list_type=" + d, i)
        });
        X("bf", "delete=1&list_type=" +
            d, i)
    };
    var zj = function(a) {
        a.Hd || (a.Hd = j, qg((L("DRAGDROP_BINARY_URL") || "") + "", r(function() {
            Q(this.Q, "mouseover", r(function() {
                this.qc || (this.qc = j, this.b.Pa() && !this.b.S() && q("yt.www.lists.dragdrop").createDraggables())
            }, this));
            var a = this.M ? "watch-tray-playlist-item" : "playlist-bar-item",
                c = q("yt.www.lists.dragdrop");
            c.init(this.Q, a);
            c.subscribe("DROPPED_AT_INDEX", this.kg, this)
        }, a)))
    };
    $.prototype.kg = function(a) {
        var b = a.sourceIndex,
            a = a.targetIndex;
        if (!(b == a || 0 > b || 0 > a)) {
            if (this.b.Cc()) {
                var c = this.b.ia();
                xj(this);
                X("bf", "copyFrom=1&action=drag&list_type=" + c, i)
            }
            c = this.b;
            if (!c.S() && c.Pa()) {
                var d = Si(c);
                d[b] && d[a] && (c.fe(b, a), d = c.C, d == b ? c.C = a : d > b && d <= a ? c.C -= 1 : d < b && d >= a && (c.C += 1));
                Oi(c)
            }
            b = ne({
                moved_item_delta: Math.abs(a - b),
                list_type: this.b.ia()
            });
            X("bf", b, i)
        }
    };
    $.prototype.next = function(a, b) {
        a || X("bf", "autoplay=1&playcount=" + this.b.hb, i);
        var c;
        c = this.b;
        var d = c.R() + 1;
        d >= c.G() && (d = 0);
        (c = Wi(c, d, !a, b)) && Rh(c)
    };
    var Aj = function(a, b) {
        if (!(1 > b || 5 < b)) {
            X("bf", "delayedautoplay=" + b, i);
            Vh(Uh.getInstance(), a.uc);
            var c = N("AUTOPLAY_WARNING" + b);
            c && yj(a, c, m, j)
        }
    };
    $.prototype.clear = function() {
        var a = this.b.ka();
        this.b.clear();
        yj(this, N("LIST_CLEARED"), m, m, function() {
            this.b.Gc(a);
            this.lb();
            X("bf", "undo_clear=1&list_type=" + b, i)
        });
        var b = this.b.ia();
        X("bf", "clear=1&list_type=" + b, i)
    };
    $.prototype.bd = function(a) {
        return this.b.bd(a)
    };
    $.prototype.lb = function(a) {
        var b = this.b.gb(),
            c = !b && !!this.b.ba,
            d = !this.b.G(),
            e = this.b.ha();
        e ? y(this.tc, "passive", "active") : y(this.tc, "active", "passive");
        var f = [],
            g = [];
        (e ? g : f).push("active");
        (!e ? g : f).push("passive");
        (b ? g : f).push("loading");
        (c ? g : f).push("editable");
        (d ? g : f).push("empty");
        y(this.k, f, g);
        b || $i(this.b, function() {
            var b = Si(this.b),
                c = this.b.R();
            this.Ma.jb(b, c, this.b, !!a, this.b.Db);
            this.qc = m
        }, this);
        b = this.b.La;
        c = this.b.ra;
        b ? yj(this, b, j, m) : c && yj(this, c, m, j);
        Vi(this.b);
        tj(this);
        this.b.Pa() && this.b.G() &&
            zj(this)
    };
    var tj = function(a, b) {
        if (!a.M) {
            var c = N("AUTOPLAY_OFF_TOOLTIP");
            pj(a) && (c = N("AUTOPLAY_ON_TOOLTIP"));
            var d = N("SHUFFLE_OFF_TOOLTIP");
            a.S() && (d = N("SHUFFLE_ON_TOOLTIP"));
            var e = N("NEXT_VIDEO_NOTHUMB_TOOLTIP");
            if (a.b.ha()) {
                var f = Si(a.b),
                    g = a.b.R();
                if ((f = (g = f[f.length == g + 1 ? 0 : g + 1]) && a.b.getVideoData(g)) && f.title) e = a.hf.jb({
                    video_encrypted_id: g
                }), e = N("NEXT_VIDEO_TOOLTIP", {
                    "{next_video_title}": f.title
                }) + " " + e
            }
            f = "";
            f = z(a.k, "min") ? N("SHOW_PLAYLIST_TOOLTIP") : N("HIDE_PLAYLIST_TOOLTIP");
            g = Uh.getInstance();
            Xh(g, a.vc,
                e);
            Xh(g, a.uc, c);
            Xh(g, a.Ed, d);
            Xh(g, a.Fd, f, b)
        }
    };
    $.prototype.yb = l;
    $.prototype.dc = l;
    var Bj = function(a) {
            a.yb && (pd(a.yb), a.yb = l)
        },
        yj = function(a, b, c, d, e) {
            b && (a.Ga || (a.Ga = a.M ? F("watch-bar-notifications") : F("playlist-bar-notifications"), Qd(a.Ga, "click", r(a.rh, a), "playlist-bar-undo")), a.dc = e || l, a.dc && (b += ' <a class="playlist-bar-undo">' + N("UNDO_LINK") + "</a>"), I("yt-alert-content", a.Ga).innerHTML = b, A(a.Ga, "yt-alert-error", !!c), A(a.Ga, "yt-alert-success", !c), U(a.Ga), Bj(a), d && (b = r(function() {
                uj(this)
            }, a), a.yb = M(b, 1E4)))
        };
    $.prototype.showError = function(a, b) {
        yj(this, a, j, b)
    };
    var uj = function(a, b) {
        var c = !!a.yb;
        if (!b || !c) V(a.Ga), a.dc = l, Bj(a)
    };
    $.prototype.rh = function(a) {
        a.stopPropagation();
        a.preventDefault();
        a = this.dc;
        uj(this);
        a.call(this)
    };
    $.prototype.Qb = function(a) {
        a = Ti(this.b, a);
        a.length && (this.Ma.Qb(a), this.Ma.scroll(4, a[0]))
    };
    var wj = function(a) {
        a.gb() || (sj(a), Cj(a, {
            g: {
                action_get_save_playlist_form: 1
            },
            f: function(a, c) {
                y(this.k, "lists", "save");
                var d = c.html;
                this.Ud = F("playlist-bar-save");
                this.Ud.innerHTML = d;
                d = F("playlist-bar-title-edit");
                d.focus();
                d.select();
                Q(F("playlist-bar-save-cancel"), "click", r(this.lg, this));
                Q(F("playlist-bar-save-form"), "submit", r(this.mg, this))
            }
        }))
    };
    $.prototype.lg = function(a) {
        a.preventDefault();
        x(this.k, "save")
    };
    $.prototype.mg = function(a) {
        a.preventDefault();
        var b = Si(this.b).join(",");
        b && (uj(this), a = $c(a.target), Cj(this, {
            g: {
                action_save_playlist: 1
            },
            method: "POST",
            sa: a + ("&video_ids=" + b),
            f: function(a, b) {
                this.Ud.innerHTML = "";
                x(this.k, "save");
                oj(this, gj("PL", b.list_id), l, j);
                yj(this, N("PLAYLIST_BAR_PLAYLIST_SAVED"), m, j)
            }
        }))
    };
    $.prototype.xf = function(a) {
        a.preventDefault();
        vj(this)
    };
    $.prototype.yf = function() {
        var a = this.b;
        Yi(a, l, {
            load_more: "1"
        });
        Oi(a)
    };
    var vj = function(a) {
        a.gb() || (sj(a), Cj(a, {
            g: {
                action_get_playlists: 1
            },
            f: function(a, c) {
                y(this.k, "save", "lists");
                this.fg = F("playlist-bar-lists-back");
                this.$f = Q(this.fg, "click", r(this.gg, this));
                var d = c.html;
                this.rb = F("playlist-bar-lists");
                this.rb.innerHTML = d;
                li(this.rb);
                this.ag = Qd(this.rb, "click", r(this.hg, this), "playlist-bar-playlist-item")
            }
        }))
    };
    $.prototype.hg = function(a) {
        a.preventDefault();
        var b = a.currentTarget;
        b && (Dj(this), a = P(b, "list-type") || "", b = P(b, "list-id") || "", oj(this, gj(a, b)))
    };
    $.prototype.gg = function(a) {
        a.preventDefault();
        Dj(this)
    };
    var Dj = function(a) {
            x(a.k, "lists");
            a.rb && (a.rb.innerHTML = "");
            Md(a.ag);
            Md(a.$f)
        },
        Cj = function(a, b) {
            w(a.k, "loading");
            Ab(b, {
                format: "JSON",
                r: function(a, b) {
                    var e = N("ERROR_OCCURRED");
                    b && b.errors && (e = b.errors[0]);
                    this.showError(e, j)
                },
                aa: function() {
                    x(this.k, "loading")
                },
                j: a
            });
            S("/playlist_bar_ajax", b)
        },
        Fj = function() {
            var a = Ej(),
                b = Si(a.b),
                c = {};
            u(b, function(a) {
                c[a] = this.b.getVideoData(a)
            }, a);
            var d = a.R(),
                e = pj(a),
                a = a.S() ? a.b.da : 0;
            return {
                autoPlay: e,
                index: d,
                shuffle: a,
                videoData: c,
                videoIds: b || []
            }
        };
    $.prototype.R = function() {
        return this.b.R()
    };
    var xj = function(a) {
        var b = a.b.ka();
        Ki([]);
        Li(b);
        var b = new ej,
            c = a.b.R();
        b.C = c;
        b.da = a.b.da;
        oj(a, b)
    };
    var Gj = l,
        Hj = m,
        Ej = function() {
            if (!Gj) {
                var a = F("playlist-bar"),
                    b = m;
                !a && L("ENABLE_WATCH6") && (a = F("watch-tray-playlist"), b = j);
                if (a) {
                    var c = a,
                        d;
                    if (z(c, "active")) {
                        d = P(c, "list-type") || "";
                        if ("QL" == d) d = new ej(j);
                        else {
                            var e = P(c, "list-id") || "",
                                f = (P(c, "video-ids") || "").split(",");
                            d = gj(d, e, f)
                        }
                        e = parseInt(P(c, "index-offset"), 10) || 0;
                        d.Db = e;
                        d.C = parseInt(L("PLAYLIST_BAR_PLAYING_INDEX"), 10) || 0;
                        d.hb = parseInt(L("LIST_AUTO_PLAY_VALUE"), 10) || 0;
                        e = 0;
                        L("SHUFFLE_ENABLED") && (e = Ii());
                        d.da = e
                    } else d = new ej(j);
                    e = P(c, "video-url");
                    d.eb = e || "";
                    c = z(c, "editable");
                    d.ba = c;
                    d.cb = F("playlist-bar-title").innerHTML;
                    d.bb = F("playlist-bar-extras-menu").innerHTML;
                    Gj = new $(a, d, b)
                }
            }
            return Gj
        },
        Ij = function(a) {
            var b = Ej();
            pj(b) && (0 < a ? (Aj(b, a), M(function() {
                Ij(a - 1)
            }, 1E3)) : b.next(m, "mr_meh"))
        },
        Jj = function(a, b, c, d) {
            if (Hj) {
                var e = Ej();
                e && (a = gj(a, b), b = j, e.ha() && (b = e.b.isEqual(a)), b ? (oj(e, a, function() {
                    e.Qb(c);
                    yj(e, d, m, j)
                }), e.show()) : yj(e, d, m, j))
            }
        },
        Kj = function(a) {
            for (var b = a.getDuration(), c = 5; 0 < c; c--) a.addCueRange("NEAR_END" + c, b - c, b - c + 1);
            a.addEventListener("onCueRangeEnter",
                "yt.www.lists.handleNearPlaybackEnd")
        },
        Lj = function() {
            if (!Hj || !Ej().ha()) return m;
            var a = Ej().ia();
            return !$a(Hi, a)
        };
    var Nj = function(a, b, c) {
            Mj({
                Jd: a,
                Id: "WL",
                zc: b,
                f: c,
                r: i,
                j: i
            })
        },
        Mj = function(a) {
            var b = {
                    video_ids: a.Jd,
                    playlist_id: a.kf || "",
                    new_playlist_name: a.Ld || "",
                    session_token: T.addto_ajax
                },
                c = {};
            a.Kd && (c["private"] = a.Kd);
            a.zc && (c.feature = a.zc);
            var d = "";
            switch (a.Id) {
                case "PL":
                    d = a.Ld ? "action_add_to_new_playlist" : "action_add_to_playlist";
                    break;
                case "FL":
                    d = "action_add_to_favorites";
                    break;
                case "WL":
                    d = "action_add_to_watch_later_list"
            }
            c[d] = 1;
            S("/addto_ajax", {
                Fb: j,
                format: "XML",
                method: "POST",
                g: c,
                z: b,
                j: a.j,
                r: a.r,
                f: a.f
            })
        };
    var Pj = function() {
            Oj("convUnsubscribeUrl")
        },
        Oj = function(a) {
            var b = L("CONVERSION_URLS_DICT");
            b && a in b && Pg(b[a])
        };
    var Qj = function(a, b, c, d, e, f, g) {
        this.c = a;
        this.Bg = b;
        this.tb = c;
        this.Jb = d;
        this.le = !isNaN(parseInt(e, 10));
        this.Oa = l;
        this.le && (this.Oa = e);
        this.yg = f || {};
        this.ke = !!g;
        window.__GOOGLEAPIS = window.__GOOGLEAPIS || {};
        window.__GOOGLEAPIS.gwidget = window.__GOOGLEAPIS.gwidget || {};
        window.__GOOGLEAPIS.gwidget.lang = this.Jb;
        this.le && (window.__GOOGLEAPIS["googleapis.config"] = window.__GOOGLEAPIS["googleapis.config"] || {}, window.__GOOGLEAPIS["googleapis.config"].sessionIndex = this.Oa);
        this.qb()
    };
    Qj.prototype.qb = function() {
        qg("https://apis.google.com/js/plusone.js", r(this.bi, this))
    };
    Qj.prototype.bi = function() {
        var a = q("gapi.plusone.render");
        if (a) {
            var b = Math.floor(1E4 * Math.random()),
                c = "__PLUS_ONE_CALLBACK_" + b,
                b = "plusone-button-" + b;
            window[c] = r(this.Rg, this);
            var d = document.createElement("div");
            d.id = b;
            c = {
                callbackName: c,
                count: "false",
                href: this.tb,
                size: "medium",
                source: "google:youtube"
            };
            this.ke && (c.db = 1);
            Ab(c, this.yg);
            this.c.appendChild(d);
            U(this.c);
            a(b, c)
        }
    };
    Qj.prototype.Rg = function(a) {
        "off" != a.state && (Rg("PLUS_ONE", this.Bg + ""), this.ke && Sh(re("https://plusone.google.com/_/+1/confirm", {
            url: a.url,
            source: "google:youtube"
        }), {
            width: 480,
            height: 550
        }))
    };
    var Sj = function(a, b) {
            var c = !!a,
                d = F("content"),
                e = F("watch-sidebar"),
                f = F("watch-video"),
                g = F("baseDiv"),
                k = L("WIDE_PLAYER_STYLES"),
                o = 0;
            "webkitTransition" in e.style ? (o = document.defaultView.getComputedStyle(e, l), o = 1E3 * parseFloat(o["-webkit-transition-duration"])) : "MozTransition" in e.style ? (o = document.defaultView.getComputedStyle(e, l), o = 1E3 * parseFloat(o.getPropertyValue("-moz-transition-duration"))) : "OTransition" in e.style && (o = document.defaultView.getComputedStyle(e, l), o = 1E3 * parseFloat(o.getPropertyValue("-o-transition-duration")));
            var v = Rj();
            if (c) {
                var C = o;
                w(d, "watch-wide");
                M(function() {
                    w(f, "wide");
                    for (var a = 0; a < k.length; ++a) w(g, k[a])
                }, C);
                v && "medium" == v.getPlaybackQuality() && !L("PREFER_LOW_QUALITY") && v.setPlaybackQuality("large")
            } else {
                C = o / 2;
                x(f, "wide");
                for (var E = 0; E < k.length; ++E) x(g, k[E]);
                M(function() {
                    x(d, "watch-wide")
                }, C);
                v && "large" == v.getPlaybackQuality() && v.setPlaybackQuality("medium");
                li(e)
            }
            Jg("masthead-utility-menulink-short", c);
            Jg("masthead-utility-menulink-long", !c);
            c = function() {
                var a = Eg(v);
                v.setSize(a.width, a.height)
            };
            b ? c() : M(c, o + 200);
            (c = q("yt.www.watch.experimental.updateWide")) && c.call()
        },
        Rj = function() {
            return L("PLAYER_REFERENCE") || F("movie_player") || F("video-player")
        },
        Tj = function(a) {
            K(a.target, "BUTTON") || a.currentTarget.click()
        },
        Uj = function() {
            var a = F("watch-player-rental-still-frame");
            V(F("watch-player"));
            U(a)
        };
    var Wj = function(a, b, c, d) {
        this.c = a;
        this.xc = m;
        a = ke() + "/share_ajax";
        S(a, {
            format: "JSON",
            g: {
                action_get_email: 1,
                video_id: c,
                list: d
            },
            f: function(a, c) {
                this.c.innerHTML = c.email_html;
                this.ua();
                this.focus();
                var d = c.sharing_binary_url;
                d && Vj(this, d, c.contacts, b)
            },
            j: this
        })
    };
    Wj.prototype.ua = function() {
        this.i = this.c.getElementsByTagName("form")[0];
        Q(this.i, "submit", r(this.Dc, this));
        I("share-email-send", this.i);
        this.ra = I("share-email-success", this.c);
        this.og = I("share-email-remail", this.ra);
        Q(this.og, "click", r(function() {
            Xj(this);
            this.focus()
        }, this));
        this.Nc = I("share-email-recipients", this.c);
        this.Mc = I("share-email-note", this.c);
        this.ce = I("share-email-preview-note", this.c);
        Q(this.Mc, "keyup", r(this.ng, this))
    };
    var Vj = function(a, b, c, d) {
        qg(b, r(function() {
            var a = q("yt.sharing.ContactTools");
            a && a.createContactTools(this.Nc, l, c, d)
        }, a))
    };
    Wj.prototype.ta = function() {
        this.i && (this.xc && Xj(this), this.focus())
    };
    Wj.prototype.focus = function() {
        this.Nc.focus()
    };
    var Xj = function(a) {
        a.xc = m;
        a.Nc.value = "";
        a.Mc.value = "";
        a.ce.innerHTML = "";
        V(a.ra);
        U(a.i)
    };
    Wj.prototype.ng = function() {
        var a = this.Mc.value,
            a = a.substring(0, 300),
            a = za(a),
            a = a.replace(/\n/g, "<br>");
        this.ce.innerHTML = a
    };
    Wj.prototype.Dc = function(a) {
        a.preventDefault();
        var b = H("button", l, this.i)[0];
        b.disabled = j;
        var c = I("share-email-captcha", this.c),
            d = I("share-email-error", this.c),
            e = I("yt-alert-content", d),
            a = ke() + le(this.i.action);
        S(a, {
            format: "JSON",
            method: "POST",
            sa: $c(this.i),
            f: function() {
                this.xc = j;
                U(this.ra);
                V(this.i);
                V(d);
                V(c)
            },
            r: function(a, b) {
                b.captcha_html && (c.innerHTML = b.captcha_html, U(c));
                b.errors && (e.innerHTML = b.errors.join("<br>"), U(d))
            },
            aa: function() {
                b.disabled = m
            },
            j: this
        })
    };
    var Zj = function(a, b, c) {
        this.c = a;
        a = ke() + "/share_ajax";
        S(a, {
            format: "JSON",
            g: {
                action_get_embed: 1,
                video_id: b,
                list: c
            },
            f: function(a, b) {
                this.c.innerHTML = b.embed_html;
                this.of = b.legacy_url;
                this.nf = b.legacy_code;
                this.mf = b.iframe_url;
                this.lf = b.iframe_code;
                this.ua();
                var c = R.getInstance();
                this.xa && (this.xa.checked = !ge(0, ai.Qd));
                this.Ib.checked = ge(0, ai.Pd);
                this.wa && (this.wa.checked = ge(0, ai.Rd));
                c = c.get("ems");
                (c in this.U ? this.U[c] : ub(this.U)).select();
                Yj(this);
                this.ta()
            },
            j: this
        })
    };
    Zj.prototype.ua = function() {
        this.Va = I("share-embed-code", this.c);
        Q(this.Va, "click", r(this.Yg, this));
        $j(this);
        ak(this)
    };
    var $j = function(a) {
            var b = I("share-embed-size-list", a.c),
                c = jc("share-embed-size-radio", b);
            a.U = {};
            u(c, function(a) {
                z(a, "share-embed-size-radio-custom") || (a = new bk(a), this.U[a.name] = a)
            }, a);
            var c = ub(a.U).width / (ub(a.U).height - 30),
                d = I("share-embed-size-radio-custom", b),
                c = new ck(d, c);
            a.U[c.name] = c;
            a.Mg = c;
            Qd(b, "click", r(a.Ng, a), "share-embed-size");
            b = I("share-embed-customize", b);
            Q(b, "keyup", r(a.Og, a))
        },
        ak = function(a) {
            var b = {},
                c = jc("share-embed-option", a.c);
            u(c, function(a) {
                b[a.name] = a
            });
            a.xa = b["show-related"];
            a.xa && Q(a.xa, "click", r(a.Eg, a));
            a.Ib = b["delayed-cookies"];
            Q(a.Ib, "click", r(a.Cg, a));
            a.ee = b["use-https"];
            Q(a.ee, "click", r(a.Gg, a));
            a.wa = b["use-flash-code"] || l;
            a.wa && Q(a.wa, "click", r(a.Fg, a))
        };
    Zj.prototype.ta = function() {
        this.focus()
    };
    Zj.prototype.focus = function() {
        this.Va && (this.Va.focus(), this.Va.select())
    };
    var Yj = function(a) {
            var b = a.lf,
                c = a.mf;
            a.wa && a.wa.checked && (b = a.nf, c = a.of);
            a.Ib.checked && (c = c.replace("youtube.com", "youtube-nocookie.com"));
            a.ee.checked && (c = c.split("//"), c[0] = "https:", c = c.join("//"));
            var d = {};
            a.xa && !a.xa.checked && (d.rel = 0);
            c = se(c, d);
            d = dk(a);
            if (!d.width || 200 > d.width) d = ub(a.U);
            b = b.replace(/__url__/g, za(c));
            b = b.replace(/__width__/g, d.width + "");
            b = b.replace(/__height__/g, d.height + "");
            b = za(b);
            b != a.Va.innerHTML && (a.Va.innerHTML = b)
        },
        dk = function(a) {
            return xb(a.U, function(a) {
                    return a.Ab.checked
                }) ||
                l
        };
    n = Zj.prototype;
    n.Eg = function() {
        var a = this.xa.checked,
            b = R.getInstance();
        he(ai.Qd, !a);
        b.save();
        Yj(this)
    };
    n.Cg = function() {
        var a = this.Ib.checked,
            b = R.getInstance();
        he(ai.Pd, a);
        b.save();
        Yj(this)
    };
    n.Gg = function() {
        Yj(this)
    };
    n.Fg = function() {
        var a = this.wa.checked,
            b = R.getInstance();
        he(ai.Rd, a);
        b.save();
        Yj(this)
    };
    n.Yg = function() {
        this.focus()
    };
    n.Ng = function(a) {
        a = this.U[I("share-embed-size-radio", a.currentTarget).value];
        a.select();
        var b = R.getInstance();
        b.set("ems", a.name);
        b.save();
        Yj(this);
        a != this.Mg && this.focus()
    };
    n.Og = function() {
        Yj(this)
    };
    var bk = function(a) {
        this.name = a.value;
        this.Ab = a;
        this.width = parseInt(P(this.Ab, "width"), 10);
        this.height = parseInt(P(this.Ab, "height"), 10)
    };
    bk.prototype.select = function() {
        this.Ab.checked = j;
        var a = K(this.Ab, "li"),
            b = K(a, "ul"),
            b = H("li", "selected", b);
        u(b, function(a) {
            x(a, "selected")
        });
        w(a, "selected")
    };
    var ck = function(a, b) {
        bk.call(this, a);
        this.Re = b;
        var c = K(a, "li");
        this.pd = I("share-embed-size-custom-width", c);
        this.od = I("share-embed-size-custom-height", c);
        Q(this.pd, "keyup", r(this.zh, this));
        Q(this.od, "keyup", r(this.yh, this))
    };
    t(ck, bk);
    ck.prototype.zh = function() {
        this.width = parseInt(this.pd.value, 10);
        this.height = (Math.floor(this.width / this.Re) || 0) + 30;
        this.od.value = this.height + ""
    };
    ck.prototype.yh = function() {
        this.height = parseInt(this.od.value, 10) - 30;
        this.width = Math.ceil(this.height * this.Re) || 0;
        this.pd.value = this.width + ""
    };
    var ek = function(a, b, c, d, e, f) {
            this.c = a;
            this.L = b || l;
            this.D = c || l;
            this.de = m;
            this.pf = e || l;
            this.qf = f || m;
            this.qb(d)
        },
        fk = function(a) {
            var b = ["h", "m", "s"],
                c = db(b);
            c.reverse();
            var d = {},
                a = a.toLowerCase().match(/\d+\s*[hms]?/g) || [],
                a = Ta(a, function(a) {
                    var b = (a.match(/[hms]/) || [""])[0];
                    return b ? (d[b] = parseInt(a.match(/\d+/)[0], 10), m) : j
                });
            for (a.reverse(); a.length && c.length;) {
                var e = c.shift();
                e in d || (d[e] = parseInt(a.shift(), 10))
            }
            if (a.length || 59 < d.s || 59 < d.m || 9 < d.h) return l;
            var f = "";
            u(b, function(a) {
                d[a] && (f += d[a] + a)
            });
            return f || l
        };
    ek.prototype.qb = function(a) {
        var b = ke() + "/share_ajax";
        S(b, {
            format: "JSON",
            g: {
                action_get_share_box: 1,
                video_id: this.L,
                list: this.D,
                new_share_layout: j
            },
            f: function(b, d) {
                this.c.innerHTML = d.share_html;
                this.jf = d.url_short;
                this.Gd = d.url_long;
                this.Jb = d.lang;
                this.Oa = l;
                "session_index" in d && (this.Oa = d.session_index);
                this.ua();
                var e = I("share-panel-services-dynamic", this.c);
                e && new Qj(e, (this.L || this.D) + "", this.Gd, this.Jb, this.Oa, this.pf, this.qf);
                a && a();
                this.ta()
            },
            j: this
        })
    };
    ek.prototype.ua = function() {
        var a = I("share-panel-show-url-options");
        Q(a, "click", r(this.Rf, this));
        a = I("share-panel-show-more");
        Q(a, "click", r(this.Of, this));
        a = I("share-panel-embed", this.c);
        Q(a, "click", r(this.Mf, this));
        a = I("share-panel-email", this.c);
        Q(a, "click", r(this.Lf, this));
        (a = I("share-panel-hangout", this.c)) && Q(a, "click", r(this.Nf, this));
        this.H = I("share-panel-url", this.c);
        Q(this.H, "click", r(this.Sf, this));
        Q(this.H, "focus", r(function() {
            w(this.H, "focused")
        }, this));
        Q(this.H, "blur", r(function() {
            x(this.H,
                "focused")
        }, this));
        this.If = I("share-panel-long-url", this.c);
        this.Ec = I("share-panel-start-at", this.c);
        this.N = I("share-panel-start-at-time", this.c);
        Q(this.N, "keyup", r(this.Tf, this));
        Q(this.N, "click", r(this.Qf, this));
        Q(this.N, "focus", r(function() {
            w(this.N, "focused")
        }, this));
        Q(this.N, "blur", r(function() {
            x(this.N, "focused")
        }, this));
        this.Vd = I("share-panel-hd", this.c);
        this.Wd = I("share-panel-include-list", this.c);
        this.Kb = I("share-panel-url-options", this.c);
        Q(this.Kb, "click", r(this.Ic, this));
        this.Bc = I("share-panel-services",
            this.c);
        this.Nd = I("share-panel-buttons", this.c);
        a = I("share-panel-show-more", this.c);
        Q(a, "click", r(this.Pf, this))
    };
    ek.prototype.ta = function() {
        this.H && !z(this.H, "focused") && (this.H.focus(), this.H.select())
    };
    var hk = function(a) {
        var b = gk;
        b.N && !z(b.N, "focused") && !b.de && (b.N.value = a, b.Ic())
    };
    n = ek.prototype;
    n.Ic = function() {
        if (!z(this.H, "focused")) {
            var a = this.jf;
            this.If.checked && (a = this.Gd);
            var b = {};
            this.Vd && this.Vd.checked && (b.hd = 1);
            this.Wd && this.Wd.checked && (b.list = this.D);
            var c = this.Ec.checked && fk(this.N.value);
            c && (b.t = c);
            a = re(a, b);
            this.H.value != a && (this.H.value = a)
        }
    };
    n.Tf = function() {
        this.de = j;
        this.Ec.checked = j;
        this.Ic()
    };
    n.Qf = function() {
        this.Ec.checked = j;
        this.N.value.match(/[1-9]/) || (this.N.value = "")
    };
    n.Sf = function() {
        this.H.select()
    };
    n.Lf = function() {
        var a = zh.getInstance();
        a.collapse(this.Nd);
        a.collapse(this.Kb);
        a.collapse(this.Bc);
        this.J && V(this.J);
        this.I || (this.I = I("share-panel-email-container", this.c));
        Lg(this.I);
        !P(this.I, "disabled") && Kg(this.I) && (this.Xd ? this.Xd.ta() : this.Xd = new Wj(this.I, this.Oa, this.L, this.D))
    };
    n.Nf = function() {
        var a = Rj();
        a && a.pauseVideo && a.pauseVideo();
        var a = re("https://talkgadget.google.com/hangouts", {
                hl: this.Jb,
                gid: "youtube",
                gd: this.L
            }),
            b = window.screen.height,
            c = Math.min(0.9 * window.screen.width, 1E3),
            b = Math.min(0.9 * b, 800);
        Rg("HANGOUT", this.L + "");
        Sh(a, {
            width: c,
            height: b
        })
    };
    n.Mf = function() {
        var a = zh.getInstance();
        a.collapse(this.Nd);
        a.collapse(this.Kb);
        a.collapse(this.Bc);
        this.I && V(this.I);
        this.J || (this.J = I("share-panel-embed-container", this.c));
        Lg(this.J);
        !P(this.J, "disabled") && Kg(this.J) && (this.Yd ? this.Yd.ta() : this.Yd = new Zj(this.J, this.L, this.D))
    };
    n.Rf = function() {
        zh.getInstance().collapse(this.Bc);
        this.I && V(this.I);
        this.J && V(this.J)
    };
    n.Of = function() {
        zh.getInstance().collapse(this.Kb);
        this.I && V(this.I);
        this.J && V(this.J)
    };
    n.Pf = function() {
        this.I && V(this.I);
        this.J && V(this.J)
    };
    var ik = function(a, b) {
        var c = L("VIDEO_ID"),
            d = {};
        a ? d.action_like_video = 1 : d.action_dislike_video = 1;
        b = b || {};
        d.video_id = c;
        d.plid = b.cg;
        c = {
            screen: ne({
                h: screen.height,
                w: screen.width,
                d: screen.colorDepth
            }),
            session_token: T.watch_actions_ajax
        };
        b.$d && (c.station_id = b.$d);
        S("/watch_actions_ajax", {
            format: "XML",
            method: "POST",
            g: d,
            z: c,
            f: b.f,
            r: b.r
        });
        a ? Oj("convLikeUrl") : Oj("convDislikeUrl")
    };
    var jk = ["left", "right", "center"],
        kk = {
            id: 0,
            Ne: 0,
            qg: 7,
            tg: 50,
            Wj: 80,
            vg: 95,
            ug: 15,
            rg: 100,
            isVisible: j,
            textAlign: jk[2],
            ie: 0,
            backgroundColor: "#080808",
            sg: "#fff",
            opacity: 0.8
        };
    var lk = function(a) {
        this.Aa = a.Qc;
        this.W = a.W || this.W
    };
    lk.prototype.Aa = 0;
    lk.prototype.W = 0;
    lk.prototype.toString = function() {
        return this.Aa + ", " + this.W
    };
    var mk = function(a) {
        lk.call(this, a);
        this.Zb = a.text || this.Zb;
        this.Pc = a.Lc || this.Pc;
        this.Ke = a.params.append || this.Ke;
        this.Me = a.params.row || this.Me;
        this.Le = a.params.hh || this.Le
    };
    t(mk, lk);
    n = mk.prototype;
    n.Zb = "";
    n.Ke = m;
    n.Me = 0;
    n.Le = 0;
    n.Pc = 0;
    n.Ub = function() {
        return this.Zb
    };
    n.toString = function() {
        return this.Aa + ", " + this.W + ": " + this.Zb
    };
    var nk = function(a) {
            var b = a.firstChild && a.firstChild.nodeValue || "",
                c = 1E3 * parseFloat(a.getAttribute("start") || 0);
            a.getAttribute("t") && (c = parseInt(a.getAttribute("t"), 10));
            var d = 1E3 * parseFloat(a.getAttribute("dur") || 0);
            a.getAttribute("d") && (d = parseFloat(a.getAttribute("d")));
            var e = parseInt(a.getAttribute("w"), 10) || 0,
                b = {
                    Qc: c,
                    W: d,
                    text: b,
                    Lc: e,
                    Ne: 5,
                    params: {}
                };
            a.getAttribute("r") && (b.params.row = parseInt(a.getAttribute("r"), 10));
            a.getAttribute("c") && (b.params.hh = parseInt(a.getAttribute("c"), 10));
            a.getAttribute("append") &&
                (b.Ne = 6, b.params.append = j);
            return new mk(b)
        },
        ok = function(a) {
            lk.call(this, a);
            this.id = a.Lc || this.id;
            this.params = a.params
        };
    t(ok, lk);
    ok.prototype.id = 0;
    ok.prototype.params = l;
    ok.prototype.he = "";
    var pk = function() {
        return new ok({
            Qc: -2147483648,
            W: 4294967295,
            params: kk
        })
    };
    var qk = function(a) {
        this.sb = [];
        this.Vc = [];
        this.ub = {};
        if (a && a && a.firstChild) switch (this.Ba = a, this.Ba.firstChild.tagName) {
            case "timedtext":
                for (var a = this.Ba.firstChild.childNodes, b = 0, c = a.length; b < c; b++) switch (a[b].tagName) {
                    case "window":
                        var d = a[b],
                            e = parseInt(d.getAttribute("id"), 10),
                            f = i;
                        a: {
                            var g = this.ub[e];
                            if (!d.getAttribute("t") && !d.getAttribute("start")) f = l;
                            else {
                                f = parseInt(d.getAttribute("t"), 10);
                                d.getAttribute("start") && (f = 1E3 * parseFloat(d.getAttribute("start")));
                                g && (g.Aa + g.W >= f ? g.W = f : g = l);
                                switch (d.getAttribute("op")) {
                                    case "kill":
                                        f =
                                            l;
                                        break a;
                                    case "define":
                                        g = l
                                }
                                g ? g.ci = j : g = pk();
                                var k = {},
                                    g = g ? g.params : kk,
                                    o = i;
                                for (o in g) k[o] = g[o];
                                d.getAttribute("id") && (k.id = d.getAttribute("id"));
                                d.getAttribute("op") && (k.di = d.getAttribute("op"));
                                d.getAttribute("rc") && (k.ug = parseInt(d.getAttribute("rc"), 10));
                                d.getAttribute("cc") && (k.rg = parseInt(d.getAttribute("cc"), 10));
                                d.getAttribute("ap") && (g = parseInt(d.getAttribute("ap"), 10), k.qg = 0 > g || 8 < g ? 7 : g);
                                d.getAttribute("ah") && (k.tg = parseInt(d.getAttribute("ah"), 10));
                                d.getAttribute("av") && (k.vg = parseInt(d.getAttribute("av"),
                                    10));
                                d.getAttribute("id") && (k.id = parseInt(d.getAttribute("id"), 10) || 0);
                                d.getAttribute("vs") && (k.isVisible = Boolean(d.getAttribute("vs")));
                                d.getAttribute("ju") && (k.textAlign = jk[parseInt(d.getAttribute("ju"), 10)]);
                                d.getAttribute("pd") && (k.ie = 1, 0 == parseInt(d.getAttribute("pd"), 10) && (k.ie = 0));
                                d.getAttribute("bc") && (k.backgroundColor = parseInt(d.getAttribute("bc"), 16));
                                d.getAttribute("bo") && (k.opacity = parseInt(d.getAttribute("bo"), 10) / 100);
                                d.getAttribute("fc") && (k.sg = parseInt(d.getAttribute("fc"), 16));
                                d.getAttribute("sd") &&
                                    (k.ei = parseInt(d.getAttribute("sd"), 10));
                                g = parseInt(d.getAttribute("d"), 10) || 1E3 * parseFloat(d.getAttribute("dur")) || 2147483647;
                                d = {
                                    Qc: f,
                                    W: g,
                                    params: k,
                                    Lc: parseInt(d.getAttribute("id"), 10)
                                };
                                f = new ok(d)
                            }
                        }
                        this.ub[e] = f;
                        this.Vc.push(f);
                        break;
                    case "text":
                        e = nk(a[b]), this.sb.push(e), d = e.Pc, this.ub[d] && (d = this.ub[d], e = e.Ub(), d.he += e)
                }
                break;
            default:
                this.Vc.push(pk());
                a = this.Ba.firstChild.childNodes;
                b = 0;
                for (c = a.length; b < c; b++) this.sb.push(nk(a[b]))
        }
    };
    qk.prototype.sb = l;
    qk.prototype.Vc = l;
    qk.prototype.ub = l;
    var sk = function(a, b) {
            this.start = a;
            this.end = b;
            rk++
        },
        rk = 0;
    sk.prototype.contains = function(a, b) {
        return a >= this.start && a < this.end && (b == l || a < b && b <= this.end)
    };
    new sk(0, 0);
    yf();
    var tk = sf("yt.player.logger");
    tk.lc(mf);
    var uk = function(a) {
        this.xe = a.languageCode;
        this.cd = a.languageName;
        this.id = a.id;
        this.Xb = a.is_default
    };
    uk.prototype.xe = l;
    uk.prototype.cd = l;
    uk.prototype.id = l;
    uk.prototype.Xb = m;
    var vk = function(a) {
        a = a || {};
        this.Ie = a.format;
        this.re = a.languageCode || "";
        this.ze = a.languageName;
        this.Ca = a.kind;
        this.ma = a.name;
        this.Xb = a.is_default
    };
    n = vk.prototype;
    n.ze = l;
    n.Ca = l;
    n.ma = l;
    n.Xb = m;
    n.Ie = 1;
    n.getName = function() {
        return this.ma
    };
    n.getFormat = function() {
        return this.Ie
    };
    var wk = function(a) {
        var b = [a.ze];
        if (a.Ca) {
            var c = "asr" == a.Ca ? N("HTML5_SUBS_TRANSCRIBED") : a.Ca;
            b.push(" (", c, ")")
        }
        a.ma && b.push(" - ", a.ma);
        a.wb && b.push(" >> ", a.wb.cd);
        return b.join("")
    };
    vk.prototype.toString = function() {
        var a = [this.re, ": ", this.ma, " (", this.Ca, ")"];
        this.wb && a.push(" >> ", this.wb.cd);
        return a.join("")
    };
    var xk = function(a) {
        this.Ae = [];
        this.Pe = [];
        this.$b = [];
        if (a && a.firstChild) {
            this.Ba = a;
            for (var a = this.Ba.getElementsByTagName("track"), b = a.length, c = 0; c < b; c++) {
                var d = parseInt(a[c].getAttribute("formats"), 10) || 1,
                    e = a[c].getAttribute("lang_code"),
                    f = a[c].getAttribute("lang_translated"),
                    g = a[c].getAttribute("name"),
                    k = a[c].getAttribute("kind") || "",
                    o = a[c].getAttribute("id"),
                    v = "true" == a[c].getAttribute("lang_default"),
                    C = "true" == a[c].getAttribute("cantran");
                this.md(new vk({
                    format: d,
                    languageCode: e,
                    languageName: f,
                    name: g,
                    kind: k,
                    id: o,
                    is_servable: j,
                    is_default: v,
                    is_translateable: C
                }))
            }
            a = this.Ba.getElementsByTagName("target");
            b = a.length;
            for (c = 0; c < b; c++) d = a[c].getAttribute("lang_code"), e = a[c].getAttribute("lang_translated"), f = a[c].getAttribute("lang_original"), g = a[c].getAttribute("id"), k = "true" == a[c].getAttribute("lang_default"), this.Pe.push(new uk({
                languageCode: d,
                languageName: e,
                languageOriginal: f,
                id: g,
                is_default: k
            }))
        }
    };
    n = xk.prototype;
    n.Ba = l;
    n.Ae = l;
    n.Pe = l;
    n.$b = l;
    n.nd = -1;
    n.Ya = function() {
        return this.$b
    };
    n.rd = function() {
        return this.nd
    };
    n.md = function(a) {
        switch (a.Ca) {
            case "asr":
                this.Ae.push(a);
                break;
            default:
                a.Xb && (this.nd = this.$b.length), this.$b.push(a)
        }
    };
    var yk = function(a, b, c, d) {
        this.na = a;
        c ? this.na = se(this.na, {
            hl: c
        }) : (a = pe(this.na).hl || "", a = a.split("_").join("-"), this.na = se(this.na, {
            hl: a
        }));
        this.L = b;
        this.He = !!d
    };
    n = yk.prototype;
    n.na = "";
    n.L = l;
    n.He = m;
    n.nd = 0;
    n.Xa = l;
    n.Ya = function() {
        return this.Xa.Ya()
    };
    n.rd = function() {
        return this.Xa.rd()
    };
    n.md = function(a) {
        this.Xa.md(a)
    };
    var Bk = function(a) {
            var b = zk,
                c = a.Xa.rd();
            0 > c || Ak(a, a.Xa.Ya()[c], b)
        },
        Ak = function(a, b, c) {
            var d = a.na,
                e = {
                    v: a.L,
                    type: "track",
                    lang: b.re,
                    name: b.getName(),
                    kind: b.Ca,
                    fmt: b.getFormat()
                };
            b.wb && (e.tlang = b.wb.xe);
            d = se(d, e);
            tk.info("Loading caption track from: " + d);
            a = r(function(a) {
                a = new qk(a.responseXML);
                c(a, b)
            }, a);
            eg(d, a)
        },
        Ck = function(a, b) {
            var c = a.na,
                d = {
                    type: "list",
                    tlangs: 1,
                    v: a.L,
                    fmts: Number(m)
                };
            a.He && (d.asrs = 1);
            c = se(c, d);
            tk.info("Getting track list from: " + c);
            d = r(function(a) {
                    this.Xa = new xk(a.responseXML);
                    b()
                },
                a);
            eg(c, d)
        };
    var Dk = l,
        Ek = l,
        Fk = -1,
        Gk = m,
        Hk = 0,
        Ik = 0,
        Kk = function(a) {
            if (Dk) a();
            else if ("true" != P(F("watch-captions-container"), "disabled")) {
                var b = new yk(L("TTS_URL"), L("VIDEO_ID"));
                Ck(b, function() {
                    Jk();
                    a()
                });
                Dk = b
            }
        },
        Jk = function() {
            var a = Dk,
                b = Dk.Ya(),
                c = b.length;
            if (0 >= c) U("watch-captions-not-found");
            else {
                var d = F("watch-captions-container");
                if (1 < c) {
                    var e = document.createElement("select");
                    e.id = "watch-captions-track-selector";
                    for (var f = 0; f < c; ++f) {
                        var g = b[f],
                            k = document.createElement("option"),
                            g = uc(wk(g));
                        k.appendChild(g);
                        k.value =
                            f;
                        e.appendChild(k)
                    }
                    Q(e, "change", function() {
                        Ak(Dk, Dk.Ya()[this.value], zk)
                    });
                    d.appendChild(e)
                }
                b = document.createElement("div");
                b.id = "captions-scrollbox";
                Q(b, "mouseover", function() {
                    Gk = j
                });
                Q(b, "mouseout", function() {
                    Gk = m
                });
                d.appendChild(b);
                Bk(a)
            }
        },
        zk = function(a, b) {
            var c = a.sb;
            Fk = -1;
            Ek = a;
            qd(Hk);
            var d = F("captions-scrollbox");
            d.innerHTML = "";
            var e = F("watch-captions-track-selector");
            if (e)
                for (var f = Dk.Ya(), g = 0, k = f.length; g < k; ++g)
                    if (f[g] == b) {
                        e.options[g].selected = j;
                        break
                    } g = 0;
            for (k = c.length; g < k; ++g) {
                var e = c[g],
                    f = document.createElement("div"),
                    o = e.Aa / 1E3;
                f.id = "cp-" + g;
                f.className = "cpline";
                O(f, "time", o + "");
                f.onmousedown = function(a) {
                    Rj().seekTo(parseInt(P(this, "time"), 10), j);
                    return Ud(a)
                };
                d.appendChild(f);
                var v = document.createElement("div");
                v.className = "cptime";
                v.innerHTML = Math.floor(o / 60) + ":" + (10 > o % 60 ? "0" : "") + Math.floor(o % 60);
                f.appendChild(v);
                o = document.createElement("div");
                o.className = "cptext";
                o.innerHTML = e.Ub();
                f.appendChild(o)
            }
            Hk = od(Lk, 500)
        },
        Lk = function() {
            for (var a = Rj().getCurrentTime(), b = Ek.sb, c = Fk, d = c;;) {
                var e =
                    0 <= d ? b[d].Aa / 1E3 : -1;
                if (a + 0.5 < e) d -= 1;
                else if (e = d + 1 < b.length ? b[d + 1].Aa / 1E3 : 1E6, a + 0.5 > e) d += 1;
                else break
            }
            d != c && (0 <= c && (a = F("cp-" + c), x(a, "cpline-highlight")), 0 <= d && (a = F("cp-" + d), w(a, "cpline-highlight")), Fk = d, Gk || Mk(F("cp-" + (3 <= d ? d - 3 : 0))))
        },
        Mk = function(a) {
            qd(Ik);
            var b = F("captions-scrollbox"),
                c = Math.min(a.offsetTop - b.offsetTop, b.scrollHeight - b.offsetHeight),
                d = 0;
            Ik = od(function() {
                    var a = c - b.scrollTop,
                        f = Math.round(100 * a / (1E3 - 50 * d));
                    Math.abs(a) <= Math.abs(f) || 20 < d ? (b.scrollTop = c, qd(Ik)) : (b.scrollTop += f, d++)
                },
                50)
        };
    var Ok = function(a, b) {
            var c = F("flag-video-form"),
                d = F("flag-video-menu");
            c && d && new Nk(c, d, a, b)
        },
        Nk = function(a, b, c, d) {
            this.i = a;
            this.l = b;
            Q(this.i, "submit", r(this.Lg, this));
            Pd(this.l, r(this.Kg, this));
            Q(F("flag-video-cancel"), "click", function(a) {
                a.preventDefault();
                c()
            });
            this.Pb = d
        };
    Nk.prototype.Kg = function(a) {
        var b;
        b = a.currentTarget;
        var c = Pk(this, b),
            a = P(b, "show-textbox-with-label"),
            d = !!P(b, "include-time"),
            e = !!P(b, "show-hate-group"),
            f = P(b, "popup-url"),
            g = P(b, "result-message") || "default";
        b = !!P(b, "no-post");
        var k = c.Wg;
        this.i.reason.value = c.reason;
        this.i.sub_reason.value = k;
        c = F("flag-video-more-info-comment");
        a && (F("flag-video-more-info-textarea-label").innerHTML = a);
        Jg(c, !!a);
        Jg(F("flag-video-more-info-time"), d);
        Jg(F("flag-video-more-info-hate-group"), e);
        f && Sh(f, {
            target: "atmfc",
            width: 900,
            height: 700,
            left: 0,
            top: 0,
            status: "yes",
            toolbar: "no",
            menubar: "no",
            location: "no"
        });
        b ? (V(this.i), Qk(g)) : (O(this.i, "result-message", g), U(this.i))
    };
    var Pk = function(a, b) {
        var c = ih.getInstance(),
            d = qh(c, a.l);
        nh(c, d);
        c = c.getContent(d);
        d = I("label", b);
        c.innerHTML = Jc(d);
        c = jc("selected", a.l);
        u(c, function(a) {
            x(a, "selected", "child-selected")
        });
        w(b, "selected");
        c = "";
        if (d = P(b, "subreason") || "") {
            var e = K(b.parentNode, "li"),
                c = P(e, "reason") || "";
            w(e, "selected", "child-selected")
        } else c = P(b, "reason") || "";
        return {
            reason: c,
            Wg: d
        }
    };
    Nk.prototype.Lg = function(a) {
        a.preventDefault();
        var b = F("flag-video-submit");
        b.disabled = j;
        var c = P(this.i, "result-message");
        S(this.i.action, {
            format: "XML",
            method: "POST",
            sa: $c(this.i),
            f: function() {
                Qk(c);
                this.Pb.call(p)
            },
            r: function(a, c) {
                Rk();
                var f = F("flag-video-error");
                c && c.error_message && (I("yt-alert-content", f).innerHTML = c.error_message);
                U(f);
                b.disabled = m
            },
            j: this
        })
    };
    var Qk = function(a) {
            Rk();
            V("flag-video-form-container");
            U("flag-video-result-" + a)
        },
        Rk = function() {
            var a = jc("flag-video-result", F("flag-video-results-container"));
            u(a, function(a) {
                V(a)
            })
        };
    var gk, Yk = function(a) {
            if (L("ALLOW_RATINGS")) {
                var b;
                if (b = !Sk()) L("YPC_CAN_RATE_VIDEO") ? b = m : (Tk("watch-actions-rental-required"), b = j), b = !b;
                b && (L("YPC_SHOW_VPPA_CONFIRM_RATING") && !confirm(N("VPPA_CONFIRM")) ? Uk() : (Vk(), ik(a, {
                    cg: L("PLAYBACK_ID"),
                    $d: L("STATION_ID"),
                    f: function(b, d) {
                        y(F("watch-like-unlike"), a ? "unliked" : "liked", a ? "liked" : "unliked");
                        Wk(d.html_content);
                        var e = F("watch-like-share-plusone");
                        e && new Qj(e, d.video_id, d.video_url, d.lang, d.session_index);
                        li(F("watch-actions-ajax"))
                    },
                    r: Xk
                })))
            } else Tk("watch-actions-ratings-disabled")
        },
        $k = function(a, b) {
            var c = a || F("watch-flag");
            if (Zk(c) && !Sk()) {
                Vk();
                var d = 0,
                    e = Rj();
                e && e.pauseVideo && (e.pauseVideo(), d = e.getCurrentTime());
                d = Math.floor(d);
                e = Math.floor(d / 60);
                d = {
                    action_get_flag_video_component: 1,
                    video_id: L("VIDEO_ID"),
                    t_mins: e,
                    t_secs: d - 60 * e
                };
                b && (d.from_dislike = 1);
                S("/watch_ajax", {
                    format: "XML",
                    method: "GET",
                    g: d,
                    f: function(a, b) {
                        Wk(b.html_content);
                        Ok(function() {
                            Uk()
                        }, function() {
                            c.disabled = j
                        })
                    },
                    r: Xk
                })
            }
        },
        bl = function(a, b) {
            if (Zk(a)) {
                var c = al("watch-actions-share");
                if (gk) Tk("watch-actions-share"),
                    gk.ta();
                else {
                    var d = L("VIDEO_ID"),
                        e = l;
                    Lj() && (e = Hj ? Ej().va() : l);
                    Vk();
                    gk = new ek(c, d, e, function() {
                        Tk("watch-actions-share")
                    })
                }
                b ? X("shareOpenedFromFlash", i, i) : X("shareOpenedFromActionBar", i, i);
                var f = Rj(),
                    g = od(function() {
                        var b = z(a, "active");
                        if (!f || !b) qd(g);
                        else {
                            for (var b = [], c = Math.floor(f.getCurrentTime()); 0 < c;) b.unshift(c % 60), c = Math.floor(c / 60);
                            for (; 2 > b.length;) b.unshift(0);
                            b = Ua(b, function(a, b) {
                                return 0 < b && 10 > a ? "0" + a : a
                            });
                            hk(b.join(":"))
                        }
                    }, 1E3);
                Oj("convShareUrl")
            }
        },
        cl = function(a, b) {
            var c = {
                action_get_addto_success: 1,
                video_id: a
            };
            b && (c.favorite = j);
            S("/watch_ajax", {
                format: "XML",
                method: "GET",
                g: c,
                f: function(a, b) {
                    Wk(b.html_content)
                },
                r: Xk
            })
        },
        Zk = function(a) {
            var b = !z(a, "active");
            Uk();
            b && (w(a, "active"), Wh(Uh.getInstance(), a));
            return b
        },
        Vk = function() {
            Tk("watch-actions-loading")
        },
        Wk = function(a) {
            al("watch-actions-ajax").innerHTML = a;
            Tk("watch-actions-ajax")
        },
        Xk = function(a, b) {
            var c = b && b.error_message;
            c || (c = N("WATCH_ERROR_MESSAGE"));
            al("watch-error-string").innerHTML = c;
            Tk("watch-actions-error")
        },
        dl = {},
        al = function(a) {
            a in dl ||
                (dl[a] = F(a));
            return dl[a]
        },
        Tk = function(a) {
            var b = al("watch-actions-area-container"),
                a = al(a),
                c = al("watch-actions-loading"),
                d = al("watch-actions-area");
            Kg(b) || (b.style.height = "0px", U(b));
            V(c);
            U(a);
            w(b, "transitioning");
            var e = d.offsetHeight + "px";
            M(function() {
                b.style.height = e
            }, 0);
            M(function() {
                b.style.height == e && (x(b, "transitioning"), b.style.height = "auto")
            }, 500)
        },
        Uk = function() {
            var a = al("watch-actions-area-container");
            V(a);
            a = jc("watch-actions-panel", a);
            u(a, function(a) {
                V(a)
            });
            al("watch-actions-ajax").innerHTML =
                "";
            var a = al("watch-actions"),
                b = al("watch-subactions"),
                a = H("button", l, a);
            b && (b = H("button", l, b), fb(eb(a), eb(b)));
            u(a, function(a) {
                x(a, "active")
            })
        },
        Sk = function() {
            return !L("LOGGED_IN") ? (Tk("watch-actions-logged-out"), j) : m
        };
    var el = function(a, b) {
        this.l = a;
        this.L = b;
        this.Wc()
    };
    el.prototype.Wc = function() {
        S("/pebbles_ajax", {
            method: "POST",
            j: this,
            g: {
                action_get_all_user_pebbles: 1,
                video_id: this.L
            },
            z: {
                session_token: T.pebbles_ajax
            },
            f: this.Kf
        })
    };
    el.prototype.Kf = function(a, b) {
        this.l.innerHTML = b.html;
        this.ld = I("add-pebble-form", this.l);
        this.Vf = I("video-id", this.ld);
        I("pebble-input", this.ld);
        Qd(this.l, "click", r(this.qh, this), "pebble");
        Q(this.ld, "submit", r(this.ph, this))
    };
    el.prototype.qh = function(a) {
        var b = a.currentTarget,
            a = I("pebble-name", b),
            c = z(b, "selected") ? {
                action_remove_pebble_from_video: 1
            } : {
                action_add_pebble_to_video: 1
            };
        S("/pebbles_ajax", {
            g: c,
            method: "POST",
            z: {
                video_id: this.Vf.value,
                pebble: a.innerText,
                session_token: T.pebbles_ajax
            },
            f: function() {
                pb(b, "selected")
            }
        })
    };
    el.prototype.ph = function(a) {
        a.preventDefault();
        var a = a.currentTarget,
            b = {};
        b.method = a.method.toUpperCase();
        if ("POST" == b.method) b.sa = $c(a);
        else {
            var c = Vc(Yc(a)),
                d = b.g || {};
            Ab(d, c);
            b.g = d
        }
        S(a.action, b)
    };
    var fl, gl, hl, il = function(a) {
        this.kb = a;
        this.l = F("shared-addto-menu");
        this.Hb = P(this.kb, "feature") || "";
        this.sc = z(this.kb, "watch");
        this.Na = P(this.kb, "video-ids") || "";
        this.A = this.Na.split(",");
        ca(hl) || (hl = z(this.l, "lightweight-panel"));
        (a = I("sign-in", this.l)) ? Q(a, "click", r(this.Jg, this)): L("DISPLAY_FLINTSTONE_PROTOTYPE") ? new el(this.l, this.Na) : this.Wc()
    };
    il.prototype.Wc = function() {
        var a = {
            action_get_dropdown: "1"
        };
        this.Hb && (a.feature = this.Hb);
        S("/addto_ajax", {
            Fb: j,
            format: "XML",
            method: "GET",
            j: this,
            g: a,
            f: function(a, c) {
                this.l.innerHTML = c.html_content || "";
                A(this.l, "ie", B);
                this.ua();
                jl(this)
            }
        })
    };
    il.prototype.ua = function() {
        this.e = {};
        this.e.list = F("addto-list-panel");
        this.e.Fc = F("addto-list-saved-panel");
        this.e.Rb = F("addto-list-error-panel");
        this.e.ja = F("addto-note-input-panel");
        this.e.Dd = F("addto-note-saving-panel");
        this.e.Zd = F("addto-note-saved-panel");
        this.e.Jc = F("addto-note-error-panel");
        this.e.fa = F("addto-create-panel");
        this.Tb = this.e.list;
        Qd(this.e.list, "click", r(this.Xf, this), "yt-uix-button-menu-item");
        this.Nb = I("playlist-save-note", this.e.ja);
        Q(this.Nb, "click", r(this.Yf, this));
        var a =
            I("close-button", this.l);
        Q(a, "click", r(this.Wf, this))
    };
    var jl = function(a) {
        a = jc("playlist-name", a.e.list);
        u(a, function(a) {
            var c = F(a),
                d = Hd(c),
                e = c.innerHTML != d;
            c.innerHTML = d;
            e && (a = Dd(a, "yt-uix-button-menu-item"), a.title = P(a, "possible-tooltip"))
        })
    };
    il.prototype.Xf = function(a) {
        a.stopPropagation();
        var b = a.currentTarget;
        Wh(Uh.getInstance(), b);
        this.Hc = m;
        var c = a = l,
            d = "",
            e = P(b, "list-action");
        e && ("create-playlist" == e ? kl(this) : ("favorites" == e ? (this.Hc = j, c = "FL", d = b.innerHTML) : "watch-later" == e ? (c = "WL", d = b.innerHTML) : (c = "PL", a = e, b = I("playlist-name", b), d = P(b, "original-html")), ll(this, c, a), "watch-later" == e || "favorites" == e || 1 < this.A.length ? (I("addto-title", this.e.Fc).innerHTML = d, x(this.l, "lightweight-panel"), ml(this, this.e.Fc, j), a = I("close-note", this.l), U(a)) :
            nl(this, d)))
    };
    var nl = function(a, b) {
            I("addto-title", a.e.ja).innerHTML = za(b);
            ml(a, a.e.ja, j);
            var c = I("close-note", a.l);
            U(c);
            var d = F("addto-note");
            Q(d, "keydown", r(a.oe, a));
            Q(d, "paste", r(a.oe, a));
            ol(a.e.ja, function() {
                d.focus()
            })
        },
        ol = function(a, b) {
            if (b) {
                var c = Ob ? "webkitTransitionEnd" : Mb ? "oTransitionEnd" : Nb ? "transitionend" : l;
                c ? Nd(a, c, function() {
                    b()
                }) : b()
            }
        };
    il.prototype.oe = function(a) {
        var b = a.target,
            c = I("addto-note-label", this.e.ja);
        M(r(function() {
            var a = /^[\s\xa0]*$/.test(b.value);
            a ? U(c) : V(c);
            !a && this.D && this.rc ? Fd(this.Nb, j) : Fd(this.Nb, m)
        }, this), 0)
    };
    var ml = function(a, b, c) {
            var c = c ? "slide" : "fade",
                d = ["fade", "slide"];
            y(a.Tb, d, c);
            y(b, d, c);
            hl && b == a.e.list && w(a.l, "lightweight-panel");
            if (z(b, "dismissed-panel")) x(b, "dismissed-panel"), x(a.Tb, "active-panel");
            else {
                for (var c = a.Tb, d = mb(c), e = m, f = 0; f < d.length; f++) "active-panel" == d[f] && (hb(d, f--, 1), e = j);
                e && (d.push("dismissed-panel"), c.className = d.join(" "))
            }
            w(b, "active-panel");
            a.Tb = b
        },
        kl = function(a) {
            ml(a, a.e.fa, j);
            pl(a);
            vd(a.l, "video-ids");
            var b = F("addto-create-playlist");
            Q(b, "keydown", r(a.Rc, a));
            Q(b, "paste",
                r(a.Rc, a));
            ol(a.e.fa, function() {
                b.focus()
            });
            var c = I("addto-create-cancel-button", a.e.fa);
            Q(c, "click", r(function() {
                ql(this)
            }, a));
            c = I("create-playlist-button", a.e.fa);
            Q(c, "click", r(a.Dg, a))
        },
        pl = function(a) {
            a.ge = I("addto-create-playlist", a.e.fa);
            a.qe = I("addto-create-playlist-label", a.e.fa);
            a.pe = I("create-playlist-button", a.e.fa);
            a.Sc = I("privacy-form", a.e.fa);
            Qd(a.Sc, "click", r(a.Rc, a), "playlist-privacy-option")
        };
    il.prototype.Dg = function() {
        var a = this.ge.value;
        this.Uf = j;
        var b = parseInt(bd(this.Sc), 10);
        ll(this, "PL", l, a, b);
        1 < this.A.length ? ql(this) : nl(this, a)
    };
    il.prototype.Rc = function() {
        M(r(function() {
            var a = /^[\s\xa0]*$/.test(this.ge.value);
            a ? U(this.qe) : V(this.qe);
            var b = bd(this.Sc);
            a || !b ? Fd(this.pe, m) : Fd(this.pe, j)
        }, this), 0)
    };
    il.prototype.Wf = function() {
        ql(this)
    };
    var ql = function(a) {
            var b = ih.getInstance(),
                c = qh(b, a.l);
            c && P(c, "video-ids") == a.Na && nh(b, c)
        },
        ll = function(a, b, c, d, e) {
            a.ca = b;
            a.sc && (Uk(), Vk());
            Mj({
                Jd: a.Na,
                Id: a.ca,
                kf: c,
                Ld: d,
                Kd: e,
                zc: a.Hb,
                f: a.Hf,
                r: a.Gf,
                j: a
            });
            vd(a.l, "video-ids");
            b = "";
            switch (a.ca) {
                case "PL":
                    b = d ? "new_pl" : "pl";
                    break;
                case "FL":
                    b = "fav";
                    break;
                case "WL":
                    b = "wl"
            }
            d = {
                list: b,
                feature: a.Hb
            };
            if ((a = K(a.kb, "a", l)) && a.href) a = pe(a.href), d.link_feature = a.feature || "";
            a = ne(d);
            X("addto", a, i)
        };
    n = il.prototype;
    n.Hf = function(a, b) {
        this.D = b.list_id || "";
        this.rc = b.setvideo_id || "";
        var c = b.html_content || "",
            d = b.list_url || "";
        if (this.D && this.rc) {
            var e = I("addto-title", this.l),
                f = document.createElement("a");
            f.href = d;
            f.innerHTML = e.innerHTML;
            xc(e);
            e.appendChild(f);
            /^[\s\xa0]*$/.test(F("addto-note").value) || Fd(this.Nb, j)
        }
        if (this.sc) cl(this.A[0], this.Hc);
        else if (d = Dd(this.kb, "ux-thumb-wrap"))(e = I("video-in-quicklist", d)) && yc(e), e = document.createElement("span"), e.className = "video-in-quicklist", e.innerHTML = c, d.appendChild(e);
        c = N("PLAYLIST_BAR_ADDED_TO_PLAYLIST");
        this.Hc && (c = N("PLAYLIST_BAR_ADDED_TO_FAVORITES"));
        Jj(this.ca, this.D, this.A, c)
    };
    n.Gf = function(a, b) {
        var c = b && b.error_message;
        if (c) {
            I("error-details", this.e.Rb).innerHTML = c;
            var c = I("show-menu-link", this.e.Rb),
                d = Q(c, "click", r(function(a) {
                    a.preventDefault();
                    Md(d);
                    x(this.e.ja, "dismissed-panel", "fade", "slide");
                    x(this.e.Fc, "dismissed-panel", "fade", "slide");
                    a = I("close-note", this.l);
                    V(a);
                    this.Uf ? ml(this, this.e.fa, j) : ml(this, this.e.list, j)
                }, this));
            if (c = F("addto-create-name")) c.disabled = m;
            x(this.e.Rb, "dismissed-panel", "fade", "slide");
            ml(this, this.e.Rb)
        } else ql(this);
        this.sc && Xk(0, b)
    };
    n.Yf = function() {
        S("/playlist_bar_ajax", {
            method: "POST",
            Fb: j,
            g: {
                action_set_playlist_item_annotation: 1
            },
            z: {
                annotation: F("addto-note").value,
                video_id: this.Na,
                playlist_id: this.D,
                setvideo_id: this.rc,
                session_token: T.playlist_bar_ajax
            },
            f: this.sf,
            r: this.rf,
            j: this
        });
        ml(this, this.e.Dd)
    };
    n.sf = function() {
        var a = I("addto-title", this.e.ja),
            b = Ad(a);
        w(b, "yt-uix-tooltip-reverse");
        I("panel-content", this.e.Zd).appendChild(b);
        ml(this, this.e.Zd);
        M(r(function() {
            Wh(Uh.getInstance(), b);
            ql(this)
        }, this), 3E3)
    };
    n.rf = function(a, b) {
        var c = b && b.errors;
        if (c) {
            var d = I("error-details", this.e.Jc);
            xc(d);
            u(c, function(a) {
                var b = document.createElement("li");
                b.innerHTML = a;
                d.appendChild(b)
            });
            var c = I("add-note-link", this.e.Jc),
                e = Q(c, "click", r(function(a) {
                    a.preventDefault();
                    Md(e);
                    x(this.e.Dd, "dismissed-panel");
                    ml(this, this.e.ja)
                }, this));
            ml(this, this.e.Jc)
        } else ql(this)
    };
    n.Jg = function() {
        var a = se("/addto_ajax", {
                action_redirect_to_signin_with_add: 1,
                list_type: "WL",
                video_ids: this.Na,
                next_url: document.location
            }),
            b = document.createElement("form");
        b.action = a;
        b.method = "POST";
        a = document.createElement("input");
        a.type = "hidden";
        a.name = "session_token";
        a.value = T.addto_ajax_logged_out;
        b.appendChild(a);
        document.body.appendChild(b);
        b.submit()
    };
    var rl = m;
    var sl = -1,
        vl = function() {
            -1 == sl && (sl = parseInt(ae("ACTIVITY", "0"), 10), Q(document, "keypress", tl), Q(document, "click", ul))
        },
        tl = function() {
            wl()
        },
        ul = function() {
            wl()
        },
        wl = function() {
            var a = oa();
            1E3 > a - sl || (sl = a, $d("ACTIVITY", "" + a))
        },
        xl = function() {
            return oa() - sl
        };
    var yl = function(a, b, c, d, e) {
            this.u = l;
            this.lh = c;
            this.$g = a;
            this.ah = b;
            this.ih = d;
            this.kh = L("GOOGLEPLUS_HOST") + (e != l ? "/u/" + e : "") + "/_/notifications/frame#pid=36";
            this.jh = F(a);
            this.ob = F(b)
        },
        zl = function(a, b, c, d) {
            return {
                onOpen: r(function(a) {
                    return a.openInto(c)
                }, a),
                onReady: r(function() {
                    b.showOnepick && d && b.showOnepick()
                }, a),
                onClose: function(a) {
                    b.hideOnepick && d && b.hideOnepick();
                    a.remove()
                }
            }
        },
        Al = function(a, b, c) {
            if ("undefined" === typeof c) a[b]();
            else a[b](c)
        };
    n = yl.prototype;
    n.qb = function(a) {
        var b = {
            setNotificationWidgetHeight: r(function(a) {
                this.jh.style.height = a
            }, this),
            setNotificationText: r(function(a) {
                this.lh(parseInt(a, 10))
            }, this),
            hideNotificationWidget: function() {
                a.hideNotificationWidget && a.hideNotificationWidget()
            },
            openSharebox: function() {},
            onError: function() {}
        };
        this.u = iframes.open(this.kh, {
            style: "iframe-style"
        }, {
            origin: window.location.protocol + "//" + window.location.hostname,
            source: "yt",
            hl: this.ih
        }, b, function() {})
    };
    n.load = function(a) {
        iframes.setHandler("iframe-style", zl(this, a, this.$g, m));
        iframes.setHandler("onepick", zl(this, a, this.ah, j));
        this.qb(a)
    };
    n.close = function() {
        Al(this.u, "onHide")
    };
    n.dd = function() {
        Al(this.u, "onShowNotificationsOnly", {
            maxWidgetHeight: 600
        })
    };
    n.fd = function() {
        var a = window.location.href; - 1 != a.indexOf("/watch?") && Al(this.u, "setPrefill", {
            items: [{
                type: "http://schema.org/VideoObject",
                id: a,
                properties: {
                    url: [a]
                }
            }]
        });
        Al(this.u, "onShowShareboxOnly", {
            maxWidgetHeight: 600
        })
    };
    var Bl = function(a, b) {
        b ? Al(a.u, "onActive") : Al(a.u, "onIdle")
    };
    var Cl = function(a, b) {
        this.pb = this.Ta = m;
        this.Kc = 0;
        this.c = F("sb-container");
        this.Sb = F("sb-button-notify");
        this.Zf = Ed("SPAN", "yt-uix-button-content", F("sb-button-notify"));
        this.ob = F("sb-onepick-target");
        this.u = new yl("sb-target", "sb-onepick-target", r(this.jg, this), a, b);
        this.u.load({
            hideNotificationWidget: r(this.ae, this),
            showOnepick: r(this.ig, this),
            hideOnepick: r(this.eg, this)
        });
        this.bg = Eg(this.ob);
        this.be();
        Q(window, "resize", r(this.be, this));
        Q(window, "click", r(this.ae, this));
        vl();
        od(r(this.dg, this), 12E4)
    };
    Cl.prototype.Xh = function(a) {
        this.Ta ? (Dl(this), Bl(this.u, j)) : (this.pb && El(this), this.dd(), Bl(this.u, m));
        Td(a)
    };
    Cl.prototype.Yh = function(a) {
        this.pb ? El(this) : (this.Ta && Dl(this), this.fd());
        Td(a)
    };
    Cl.prototype.dd = function() {
        this.u.close();
        Fl(this, j, "notif");
        w(this.Sb, "sb-notif-clicked");
        this.u.dd();
        this.Ta = j
    };
    Cl.prototype.fd = function() {
        this.u.close();
        Fl(this, j, "sharebox");
        this.u.fd();
        this.pb = j
    };
    var Fl = function(a, b, c) {
            c = "sb-card-" + c;
            b ? (y(a.c, "sb-off", "sb-on"), w(a.c, c)) : (y(a.c, "sb-on", "sb-off"), x(a.c, c))
        },
        El = function(a) {
            Fl(a, m, "sharebox");
            a.pb = m
        },
        Dl = function(a) {
            Fl(a, m, "notif");
            a.Ta = m;
            x(a.Sb, "sb-notif-clicked")
        };
    Cl.prototype.ae = function() {
        if (this.Ta || this.pb) this.u.close(), Dl(this), El(this), Gl(this)
    };
    Cl.prototype.jg = function(a) {
        this.Kc = a;
        Gl(this)
    };
    var Gl = function(a) {
        a.Ta || (Dc(a.Zf, a.Kc + ""), 0 == a.Kc ? y(a.Sb, "sb-notif-on", "sb-notif-off") : y(a.Sb, "sb-notif-off", "sb-notif-on"))
    };
    Cl.prototype.ig = function() {
        y(this.ob, "sb-off", "sb-on")
    };
    Cl.prototype.eg = function() {
        y(this.ob, "sb-on", "sb-off")
    };
    Cl.prototype.be = function() {
        var a = Math.max((nc(window).height - this.bg.height) / 2, 0),
            b = this.ob;
        ga("top") ? tg(b, a + "px", "top") : tb("top", na(tg, b))
    };
    Cl.prototype.dg = function() {
        6E5 < xl() ? Bl(this.u, m) : Bl(this.u, j)
    };
    var Hl = function(a) {
        for (var b = Bc(F("picker-container")); b;) a && a != b.id && V(b), b = b.nextElementSibling != i ? b.nextElementSibling : zc(b.nextSibling)
    };
    var Il = l,
        Jl = l,
        Kl = function(a) {
            if (!Cd(a.relatedTarget, a.currentTarget) && (a = K(a.target, l, "lego"), a = P(a, "lego-name"))) Il.innerHTML = "", Il.innerHTML = ", " + a
        },
        Ll = function(a) {
            Cd(a.relatedTarget, a.currentTarget) || (Il.innerHTML = "")
        },
        Ml = function(a) {
            if (!Cd(a.relatedTarget, a.currentTarget)) {
                var a = K(a.target, l, "lego"),
                    b = P(a, "lego-name");
                b && (z(a, "append-lego") ? (Il.innerHTML = "", Il.innerHTML = ", " + b) : (Il.innerHTML = "", w(Jl, "replace-lego-preview"), Il.innerHTML = b))
            }
        },
        Nl = function(a) {
            Cd(a.relatedTarget, a.currentTarget) ||
                (Il.innerHTML = "", x(Jl, "replace-lego-preview"))
        };
    var Ol, Pl, Ql = function(a, b) {
        this.Pb = a;
        this.nc = b;
        this.userData = {}
    };
    Ql.prototype.Te = function() {
        return {}
    };
    Ql.prototype.ff = function(a) {
        this.Pb(this, a)
    };
    Ql.prototype.vd = function(a) {
        this.nc(this, a)
    };
    var Rl = function(a, b) {
        Ql.call(this, a, b)
    };
    t(Rl, Ql);
    Rl.prototype.tb = "/subscription_ajax";
    Rl.prototype.ab = {};
    var Sl = function(a, b) {
        a.tb = re(a.tb, b)
    };
    Rl.prototype.Te = function() {
        return this.ab
    };
    Rl.prototype.ff = function(a) {
        var b = l,
            c = [N("SUBSCRIBE_SERVER_ERROR")];
        try {
            b = Vf(a.responseText)
        } catch (d) {}(a = b && b.response) ? this.Pb(this, a): this.nc(this, c)
    };
    Rl.prototype.vd = function(a, b) {
        var c = b ? [b] : [N("SUBSCRIBE_SERVER_ERROR")];
        try {
            var d = Vf(a.responseText)
        } catch (e) {
            this.nc(this, c);
            return
        }
        this.nc(this, d.errors)
    };
    var Tl = function(a) {
            Ol = jg;
            Pl = a
        },
        Ul = l,
        Vl = [],
        Xl = function(a) {
            Vl.push(a);
            Ul || Wl()
        },
        Wl = function() {
            if (Vl.length && !Ul) {
                var a = Vl.shift();
                if (a) {
                    var b = a.Te();
                    b || (b = {});
                    b.session_token = Pl;
                    try {
                        var c = {
                            method: "POST",
                            postBody: ne(b),
                            onComplete: Yl,
                            onException: Zl,
                            onError: Zl
                        }
                    } catch (d) {
                        a.vd({}, N("SUBSCRIBE_JS_ERROR"));
                        return
                    }
                    Ul = a;
                    Ol(a.tb, c)
                }
            }
        },
        Yl = function(a) {
            var b = Ul;
            Ul = l;
            Wl();
            b && b.ff(a)
        },
        Zl = function(a) {
            var b = Ul;
            Ul = l;
            Wl();
            b && b.vd(a)
        };
    var bm = function(a) {
            var b = $l(a);
            "button" == P(b, "subscription-menu-type") ? (b = ih.getInstance(), b = jh(b, a), I("subscription-menu-loader", b) && (a.loader = b.innerHTML), am(a)) : (b = $l(a), b = F(P(b, "subscription-expandable-id")), Kg(b) ? (V(b), w(a, "yt-uix-expander-collapsed")) : (am(a), U(b), x(a, "yt-uix-expander-collapsed")))
        },
        am = function(a) {
            if (!P(a, "loaded")) {
                var b = $l(a),
                    c = P(b, "subscription-type"),
                    d = P(b, "subscription-xsrf") || "",
                    e = P(b, "subscription-menu-type"),
                    f = {},
                    g = P(b, "subscription-value");
                "playlist" == c ? (f.action_get_subscription_form_for_playlist =
                    1, c = "p") : "blog" == c ? (f.action_get_subscription_form_for_blog = 1, c = "b") : "topic" == c ? (f.action_get_subscription_form_for_topic = 1, c = "l") : (f.action_get_subscription_form_for_user = 1, c = "u");
                Tl(d);
                d = new Rl(cm, function() {
                    dm(b, j)
                });
                Sl(d, f);
                f = {};
                f[c] = g;
                f.menu_type = e;
                d.ab = f || {};
                d.userData.eventTrigger = a;
                d.userData.subscription = b;
                Xl(d)
            }
        },
        $l = function(a) {
            return K(a, l, "subscription-container")
        },
        em = function(a) {
            var b = K(a, l, "subscription-menu-expandable");
            if (b) return b.expandableMenuSubscription;
            a = K(a, l, "yt-uix-button-menu");
            b = ih.getInstance();
            a = qh(b, a);
            return $l(a)
        },
        fm = function(a) {
            var b = jc("subscription-container"),
                c = P(a, "subscription-type"),
                d = P(a, "subscription-value");
            return b = Ta(b, function(a) {
                if (P(a, "subscription-type") == c && P(a, "subscription-value") == d) return j
            })
        },
        dm = function(a, b) {
            var c = P(a, "subscription-id"),
                d = fm(a);
            u(d, function(a) {
                if (b) {
                    "button" == P(a, "subscription-menu-type") ? gm(a) : hm(a, j);
                    var d = Ed(l, "subscription-subscribed-container", a),
                        g = Ed(l, "subscribe-button", a),
                        k = Ed(l, "subscription-options-button", a);
                    vd(a,
                        "subscription-id");
                    V(d);
                    U(g);
                    vd(k, "loaded")
                } else d = Ed(l, "subscription-subscribed-container", a), g = Ed(l, "subscribe-button", a), O(a, "subscription-id", c), V(g), U(d)
            })
        },
        im = function(a, b) {
            if ("button" == P(a, "subscription-menu-type")) {
                var c = I("subscription-options-button", a),
                    d = ih.getInstance(),
                    e = jh(d, c);
                e && (e.innerHTML = b, rh(d, c, e))
            } else c = F(P(a, "subscription-expandable-id")), d = I("subscription-menu-loader", c), e = I("subscription-menu-body", c), e.innerHTML = b, c.expandableMenuSubscription = a, V(d), U(e)
        },
        gm = function(a) {
            var a =
                I("subscription-options-button", a),
                b = ih.getInstance();
            jh(b, a);
            nh(b, a);
            if (a.loader) {
                var c = a.loader,
                    d = jh(b, a);
                d && (d.innerHTML = c, rh(b, a, d))
            }
            vd(a, "loaded")
        },
        hm = function(a, b) {
            var c = zh.getInstance(),
                d = I("yt-uix-expander", a),
                e = F(P(a, "subscription-expandable-id"));
            if (b) {
                var f = I("subscription-menu-loader", e),
                    g = I("subscription-menu-body", e);
                vd(d, "loaded");
                U(f);
                V(g);
                g.innerHTML = ""
            }
            Kg(e) && c.collapse(d)
        },
        jm = function(a, b) {
            var c = a.userData.subscription,
                d = b.html_content;
            a.userData.eventTrigger.disabled = m;
            O(c, "subscription-id",
                b.id);
            dm(c, m);
            d && (im(c, d), d = I("subscription-options-button", c), O(d, "loaded", "true"), bm(d), "button" == P(c, "subscription-menu-type") && ih.getInstance().Bd(d))
        },
        km = function(a) {
            yc(a.userData.collection)
        },
        lm = function(a) {
            dm(a.userData.subscription, j)
        },
        cm = function(a, b) {
            var c = a.userData.eventTrigger,
                d = a.userData.subscription,
                e = b.html_content;
            O(d, "subscription-id", b.id);
            im(d, e);
            O(c, "loaded", "true")
        },
        mm = function(a) {
            var b = a.userData.subscription;
            a.userData.eventTrigger.disabled = m;
            a = fm(b);
            u(a, function(a) {
                "button" ==
                P(a, "subscription-menu-type") ? gm(a) : hm(a, j)
            })
        },
        nm = function(a) {
            if (a = a.userData.eventTrigger) a.disabled = m
        };
    var om = function(a, b) {
        this.n = a;
        this.Mb = b || l;
        this.Ra = P(a, "subscription-type") || "user";
        this.Ac = P(a, "subscription-value") || "";
        this.Qg = !!P(a, "enable-tooltip");
        this.Lb = !!P(a, "enable-hovercard");
        this.nb = m;
        this.Oc()
    };
    om.prototype.getId = function() {
        return P(this.n, "subscription-id") || l
    };
    var qm = function(a, b) {
        b ? O(a.n, "subscription-id", b) : vd(a.n, "subscription-id");
        pm(a)
    };
    om.prototype.getValue = function() {
        return this.Ac
    };
    om.prototype.getType = function() {
        return this.Ra
    };
    var pm = function(a) {
        A(a.n, "subscribed", !!a.getId());
        var b = Y(Ch.getInstance(), "target");
        A(a.n, b, !!a.getId() && a.Lb);
        a.Qg && (b = (a.getId() ? "un" : "") + "subscribe-tooltip", b = P(a.n, b) || "", Xh(Uh.getInstance(), a.n, b))
    };
    n = om.prototype;
    n.Oc = function() {
        Q(this.n, "click", r(this.mh, this));
        Me("SUBSCRIBE", this.Ce, this);
        Me("SUBSCRIBE", this.Td, this);
        Me("UNSUBSCRIBE", this.Ce, this);
        this.Lb && Q(this.n, "mouseover", r(this.nh, this));
        pm(this)
    };
    n.Ce = function(a, b, c) {
        c != this.getId() && this.getValue() == a && this.getType() == b && qm(this, c)
    };
    n.Td = function() {
        this.Lb && Ch.getInstance().ea(this.n)
    };
    n.mh = function() {
        if (this.nb) return m;
        Wh(Uh.getInstance(), this.n);
        this.getId() ? rm(this) : sm(this)
    };
    n.nh = function() {
        this.getId() && M(r(function() {
            tm(this)
        }, this), 350)
    };
    var tm = function(a) {
            var b = Ch.getInstance();
            if (!a.Sd && xh(b, a.n)) {
                a.Sd = j;
                var c = {
                    hovercard: 1
                };
                c["action_get_subscription_form_for_" + a.Ra] = 1;
                var d = {
                    session_token: T.subscription_ajax
                };
                d[um(a)] = a.Ac;
                S("/subscription_ajax", {
                    method: "POST",
                    g: c,
                    z: d,
                    j: a,
                    f: function(a, c) {
                        var d = this.n,
                            k = c.response.html_content,
                            o = hh(b, d);
                        if (o) {
                            var v = b.mb(o);
                            v && (v.innerHTML = k, z(o, Y(b, "active")) && (k = vh(b, d, o), wh(b, d, k)))
                        }
                        vm(this)
                    },
                    r: function() {
                        this.Sd = m
                    }
                })
            }
        },
        vm = function(a) {
            var b = Ch.getInstance(),
                c = hh(b, a.n),
                d = b.mb(c);
            u(d.getElementsByTagName("input"),
                function(a) {
                    Q(a, "change", r(function() {
                        var a = d.getElementsByTagName("form")[0],
                            b = a.action || document.location.href,
                            c = a.method.toUpperCase() || "GET",
                            a = $c(a);
                        eg(b, i, c, a, i)
                    }, this))
                }, a)
        },
        sm = function(a) {
            if (L("LOGGED_IN")) {
                var b = um(a),
                    c = {};
                c["action_create_subscription_to_" + a.Ra] = 1;
                var d = P(a.n, "subscription-feature");
                d && (c.feature = d);
                d = {
                    session_token: T.subscription_ajax
                };
                d[b] = a.Ac;
                a.nb = j;
                a.n.disabled = j;
                S("/subscription_ajax", {
                    method: "POST",
                    j: a,
                    g: c,
                    z: d,
                    f: function(a, b) {
                        qm(this, b.response.id);
                        Ne("SUBSCRIBE",
                            this.getValue(), this.getType(), this.getId());
                        this.Lb && (Ch.getInstance().show(this.n), tm(this));
                        this.Mb && this.Mb(this.n, j)
                    },
                    aa: function() {
                        this.nb = m;
                        this.n.disabled = m
                    }
                });
                Oj("convSubscribeUrl")
            }
        },
        rm = function(a) {
            var b = {
                    s: a.getId(),
                    session_token: T.subscription_ajax
                },
                c = {
                    action_remove_subscriptions: 1
                },
                d = P(a.n, "subscription-feature");
            d && (c.feature = d);
            a.nb = j;
            a.n.disabled = j;
            S("/subscription_ajax", {
                method: "POST",
                j: a,
                g: c,
                z: b,
                f: function() {
                    qm(this, l);
                    this.Td();
                    Ne("UNSUBSCRIBE", this.getValue(), this.getType(), l);
                    this.Mb && this.Mb(this.n, m)
                },
                aa: function() {
                    this.nb = m;
                    this.n.disabled = m
                }
            });
            Pj()
        },
        um = function(a) {
            return "playlist" == a.Ra ? "p" : "blog" == a.Ra ? "b" : "topic" == a.Ra ? "l" : "u"
        };
    var wm = function() {};
    var xm = function() {};
    t(xm, wm);
    xm.prototype.G = function() {
        var a = 0;
        Qc(this.$a(j), function(b) {
            Qa(b);
            a++
        });
        return a
    };
    xm.prototype.clear = function() {
        var a = Rc(this.$a(j)),
            b = this;
        u(a, function(a) {
            b.remove(a)
        })
    };
    var ym = function(a) {
        this.$ = a
    };
    t(ym, xm);
    n = ym.prototype;
    n.set = function(a, b) {
        try {
            this.$.setItem(a, b)
        } catch (c) {
            h("Storage mechanism: Quota exceeded")
        }
    };
    n.get = function(a) {
        a = this.$.getItem(a);
        if (ga(a) || a === l) return a;
        h("Storage mechanism: Invalid value was encountered")
    };
    n.remove = function(a) {
        this.$.removeItem(a)
    };
    n.G = function() {
        return this.$.length
    };
    n.$a = function(a) {
        var b = 0,
            c = new Oc,
            d = this;
        c.next = function() {
            b >= d.G() && h(Nc);
            var c = Qa(d.$.key(b++));
            if (a) return c;
            c = d.$.getItem(c);
            if (ga(c)) return c;
            h("Storage mechanism: Invalid value was encountered")
        };
        return c
    };
    n.clear = function() {
        this.$.clear()
    };
    var zm = function() {
        var a = l;
        try {
            a = window.localStorage || l
        } catch (b) {}
        this.$ = a
    };
    t(zm, ym);
    var Am = function(a) {
        this.Cb = a;
        this.Xe = new Xf
    };
    n = Am.prototype;
    n.Cb = l;
    n.Xe = l;
    n.set = function(a, b) {
        ca(b) ? this.Cb.set(a, Zf(this.Xe, b)) : this.Cb.remove(a)
    };
    n.get = function(a) {
        a = this.Cb.get(a);
        if (a !== l) try {
            return Vf(a)
        } catch (b) {
            h("Storage: Invalid value was encountered")
        }
    };
    n.remove = function(a) {
        this.Cb.remove(a)
    };
    var Bm = function() {
        var a = new zm,
            b;
        if (b = a) a: {
            try {
                b = !!a.$ && !!a.$.getItem;
                break a
            } catch (c) {}
            b = m
        }
        b && (this.Za = new Am(a))
    };
    Bm.prototype.Za = l;
    Bm.prototype.getVolume = function() {
        var a = {
            volume: 100,
            muted: m
        };
        if (this.Za) {
            var b = {};
            try {
                b = this.Za.get("yt-player-volume") || {}
            } catch (c) {
                this.Za.remove("yt-player-volume")
            }
            a.volume = isNaN(b.volume) ? 100 : Math.min(Math.max(b.volume, 0), 100);
            a.muted = b.muted == i ? m : b.muted
        }
        return a
    };
    !document.getElementById && document.all && (document.getElementById = function(a) {
        return document.all[a]
    });
    var Cm = m,
        Dm = -1,
        Em = "",
        Fm = {},
        Gm = new Ke,
        Hm = l,
        Im = function() {
            return L("RESUME_COOKIE_NAME")
        },
        Jm = function(a, b) {
            var c = Im();
            if (c) {
                var d = ae(c, "").split(","),
                    d = Ta(d, function(b) {
                        return 0 != b.indexOf(a) && b.length
                    });
                4 <= d.length && d.shift();
                d.push(a + ":" + b);
                $d(c, d.join(","), 1814400)
            }
        },
        Km = function(a) {
            var b = Im();
            if (b) {
                var c = ae(b, "").split(","),
                    c = Ta(c, function(b) {
                        return 0 != b.indexOf(a)
                    });
                0 == c.length ? be(b) : $d(b, c.join(","), 1814400)
            }
        },
        Lm = function() {
            var a = Rj(),
                b = a.getDuration(),
                a = Math.floor(a.getCurrentTime()),
                c = L("VIDEO_ID");
            120 >= a || a + 120 >= b ? Km(c) : Jm(c, Math.floor(a))
        },
        Mm = function(a, b) {
            if (rd && !a.addEventListener) {
                var c = b ? 2 * b : 50;
                M(function() {
                    Mm(a, c)
                }, c)
            } else b && X("ael_delayed", "delay=" + b, i), Nm(a), Gm.gc("READY_STATE_TOPIC", a)
        },
        Nm = function(a) {
            a.addEventListener("onStateChange", "handleWatchPagePlayerStateChange");
            a.addEventListener("onPlaybackQualityChange", "onPlayerFormatChanged");
            a.addEventListener("NEXT_CLICKED", "yt.www.watch.player.onPlayerNextClicked");
            a.addEventListener("SETTINGS_CHANGED", "yt.www.watch.player.onPlayerSettingsChanged");
            a.addEventListener("SIZE_CLICKED", "yt.www.watch.player.onPlayerSizeClicked");
            a.addEventListener("onVolumeChange", "yt.www.watch.player.onVolumeChange");
            a.addEventListener("NEXT_CLICKED", "yt.www.watch.player.onPlayerNextClicked");
            a.addEventListener("SHARE_CLICKED", "yt.www.watch.player.onPlayerShareClicked");
            Im() && Q(window, "beforeunload", Lm);
            qe(window.location.hash).q && L("WIDE_PLAYER_STYLES") && Sj(m);
            var b = F("watch-player");
            b && (b.style.background = "transparent");
            Kj(a);
            Hm = new Bm
        },
        Om = function(a, b) {
            var c =
                b != l ? b : j,
                d = Rj();
            d.seekTo(a, j);
            c && (F("watch-video-container") ? window.scroll(0, 0) : window.location.href = "#movie_player");
            d.playVideo()
        },
        Qm = function() {
            if (Cm) {
                var a = window.location.hash;
                a != Em && (Em = a, a = qe(a), "t" in a && a.t != Fm.t && Om(Pm(a.t), m), Fm = a)
            }
        },
        Pm = function(a) {
            var b = 0; - 1 != a.indexOf("h") && (a = a.split("h"), b = 3600 * a[0], a = a[1]); - 1 != a.indexOf("m") && (a = a.split("m"), b = 60 * a[0] + b, a = a[1]); - 1 != a.indexOf("s") ? (a = a.split("s"), b = 1 * a[0] + b) : b = 1 * a + b;
            return b
        },
        Rm = function() {
            var a = F("gpu-pilot-light");
            a || (a = document.createElement("canvas"),
                a.id = "gpu-pilot-light", a.style.cssText = "width:0px; height:0px; -webkit-transform:rotateY(0deg)", document.body.appendChild(a))
        };
    var Sm = m,
        Tm = l,
        Um = l,
        Vm = l,
        Wm = l,
        Xm = function(a) {
            a.addEventListener("onStateChange", "yt.www.watch.abandonment.onPlayerStateChange");
            a.addEventListener("onError", "yt.www.watch.abandonment.onError")
        },
        Ym = function(a) {
            var b = Wm,
                a = {
                    event: a,
                    aplid: Tm,
                    abt: Um,
                    evtm: Math.round(oa() - b)
                };
            (b = L("PLAYER_CONFIG")) && b.args && b.args.plid && (a.plid = b.args.plid);
            b && b.args && b.args.video_id && (a.v = b.args.video_id);
            Pg("/player_204?" + ne(a))
        },
        Zm = function() {
            Vm && Ym("userwaiting");
            Vm = l
        },
        $m = function() {
            Sm && Ym("unload")
        };
    var an = function() {
            var a = F("watch-video-annotation-editable"),
                b = ra(F("watch-video-annotation-content").innerHTML);
            b ? y(a, ["unannotated", "editing"], ["annotated", "not-editing"]) : y(a, ["annotated", "not-editing"], ["unannotated", "editing"]);
            F("watch-video-annotation-textarea").value = b
        },
        bn = function(a) {
            if (!P(a, "saving")) {
                O(a, "saving", "true");
                var b = F("watch-video-annotation-content"),
                    c = Ed("textarea", l, a),
                    d = ra(c.value),
                    e = {};
                d || (e["delete"] = 1);
                S(a.action, {
                    format: "JSON",
                    method: "POST",
                    g: e,
                    sa: $c(a),
                    f: function() {
                        b.innerHTML =
                            za(d)
                    },
                    r: function() {
                        c.value = b.innerHTML
                    },
                    aa: function() {
                        vd(a, "saving");
                        an()
                    }
                })
            }
        };
    var cn = function(a) {
        var b = a.target;
        if (!("INPUT" == b.tagName || "TEXTAREA" == b.tagName || "SELECT" == b.tagName)) switch (a.keyCode) {
            case 110:
                Ej().next(j, "keys");
                break;
            case 112:
                a = Ej();
                (a = Xi(a.b, "keys")) && Rh(a);
                break;
            case 106:
                if (a = Rj()) b = a.getCurrentTime(), a.seekTo(b - 10);
                break;
            case 108:
                if (a = Rj()) b = a.getCurrentTime(), a.seekTo(b + 10);
                break;
            case 107:
                (a = Rj()) && (2 == Dm ? a.playVideo() : a.pauseVideo())
        }
    };
    var dn = function() {
        var a = R.getInstance();
        he(ai.$e, j);
        a.save();
        V("watch_page_survey")
    };
    var en = function(a, b, c) {
        var d = a.serverUri || "//www.google.com/tools/feedback",
            e = p.GOOGLE_FEEDBACK_START;
        p.GOOGLE_FEEDBACK_START_ARGUMENTS = arguments;
        e ? e.apply(p, arguments) : (e = document.createElement("script"), e.src = d + "/load.js", document.body.appendChild(e))
    };
    var fn;
    (function() {
        function a(a) {
            a = a.match(/[\d]+/g);
            a.length = 3;
            return a.join(".")
        }
        var b = m,
            c = "";
        if (navigator.plugins && navigator.plugins.length) {
            var d = navigator.plugins["Shockwave Flash"];
            d && (b = j, d.description && (c = a(d.description)));
            navigator.plugins["Shockwave Flash 2.0"] && (b = j, c = "2.0.0.11")
        } else if (navigator.mimeTypes && navigator.mimeTypes.length)(b = (d = navigator.mimeTypes["application/x-shockwave-flash"]) && d.enabledPlugin) && (c = a(d.enabledPlugin.description));
        else try {
            d = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.7"), b =
                j, c = a(d.GetVariable("$version"))
        } catch (e) {
            try {
                d = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.6"), b = j, c = "6.0.21"
            } catch (f) {
                try {
                    d = new ActiveXObject("ShockwaveFlash.ShockwaveFlash"), b = j, c = a(d.GetVariable("$version"))
                } catch (g) {}
            }
        }
        fn = c
    })();
    var gn, hn, jn, kn, ln, mn, nn;
    nn = mn = ln = kn = jn = hn = gn = m;
    var on = Ib();
    on && (-1 != on.indexOf("Firefox") ? gn = j : -1 != on.indexOf("Camino") ? hn = j : -1 != on.indexOf("iPhone") || -1 != on.indexOf("iPod") ? jn = j : -1 != on.indexOf("iPad") ? kn = j : -1 != on.indexOf("Android") ? ln = j : -1 != on.indexOf("Chrome") ? mn = j : -1 != on.indexOf("Safari") && (nn = j));
    var pn = gn,
        qn = hn,
        rn = jn,
        sn = kn,
        tn = ln,
        un = mn,
        vn = nn;
    var wn;
    a: {
        var xn = "",
            yn, zn;
        if (pn) yn = /Firefox\/([0-9.]+)/;
        else {
            if (B || Mb) {
                wn = $b;
                break a
            }
            un ? yn = /Chrome\/([0-9.]+)/ : vn ? yn = /Version\/([0-9.]+)/ : rn || sn ? (yn = /Version\/(\S+).*Mobile\/(\S+)/, zn = j) : tn ? yn = /Android\s+([0-9.]+)(?:.*Version\/([0-9.]+))?/ : qn && (yn = /Camino\/([0-9.]+)/)
        }
        if (yn) var An = yn.exec(Ib()),
            xn = An ? zn ? An[1] + "." + An[2] : An[2] || An[1] : "";wn = xn
    }
    var Bn = wn;
    var Cn = function() {
        (B ? 0 <= Ia(Bn, "7") && 0 <= Ia(fn, "9") : pn ? 0 <= Ia(Bn, "3.5") : vn ? 0 <= Ia(Bn, "5") : un) || V("reportbug")
    };
    var En = function() {
            Nd(F("help-button"), "click", Dn, j)
        },
        Dn = function() {
            var a = F("help-button");
            if (a) {
                var b = P(a, "iph-topic-id"),
                    c = L("LOCALE"),
                    d = P(a, "iph-title-text"),
                    e = P(a, "iph-search-button-text"),
                    f = P(a, "iph-anchor-text"),
                    g = document.location.protocol + P(a, "iph-js-url"),
                    k = document.location.protocol + P(a, "iph-css-url");
                g && k && (k = rc("link", {
                    href: k,
                    rel: "stylesheet"
                }), document.getElementsByTagName("head")[0].appendChild(k), qg(g, function() {
                    var g = q("yt.www.help.init"),
                        k = q("yt.www.help.onButtonClick");
                    g(b, "http://www.google.com",
                        "/support/youtube", c, d, e, f);
                    Q(a, "click", k);
                    k()
                }))
            }
        };
    var Fn = function() {
            (function() {
                try {
                    for (var a = this; !(a.parent == a);) {
                        "$" == a.frameElement.src && h("odd");
                        a = a.parent
                    }
                    a.frameElement != l && h("busted")
                } catch (b) {
                    document.write("--\><plaintext style=display:none><\!--"), window.open("/", "_top"), top.location = "/"
                }
            })()
        },
        Gn = function(a) {
            "block" == a.responseText && Fn()
        };
    if (window != window.top) {
        var Hn = document.referrer;
        window.parent != window.top ? Fn() : te(Hn) || jg("/roger_rabbit?" + ("location=" + encodeURIComponent(Hn) + "&self=" + encodeURIComponent(window.location.href)), {
            onComplete: Gn
        })
    };
    var In = m,
        Jn = function() {
            Ne("init");
            M(function() {
                a: if ("localhost" === document.domain) {
                    for (var a = [], b = (document.cookie || "").split(/\s*;\s*/), c = 0, d; d = b[c]; c++) 0 == d.indexOf("PREF=") && a.push(d.substr(5));
                    for (b = 0; b < a.length; b++)
                        if (0 == a[b].indexOf("ID=")) {
                            be("PREF", "/", ".localhost");
                            X("bad_pref_cookie_removed", i, i);
                            break a
                        }
                }
            }, 500)
        },
        Kn = function() {
            Ne("dispose")
        },
        Mn = function(a, b, c) {
            var d = F("www-core-js");
            !In && d && -1 != d.src.indexOf("/debug-") && (c = Ln(c), X("jserror", "error=" + encodeURIComponent(a) + "&script=" +
                encodeURIComponent(b) + "&linenumber=" + encodeURIComponent(c) + "&url=" + encodeURIComponent(window.location.href), i), In = j)
        },
        Ln = function(a) {
            if (Nb) try {
                eval("(0)()")
            } catch (b) {
                return b.stack.replace(/(.*):/g, "").replace(/\n/g, ",")
            } else return a
        };
    var Nn = [];
    var On = function(a, b, c, d) {
            window.google_ad_client = a;
            window.google_ad_channel = b;
            window.google_max_num_ads = 1;
            window.google_ad_output = "js";
            window.google_ad_type = "text";
            window.google_only_pyv_ads = j;
            c && (window.google_kw = c, window.google_kw_type = "broad");
            window.dclk_language && (window.google_language = window.dclk_language);
            window.google_ad_request_done = d;
            document.write('<script language="JavaScript" src="//pagead2.googlesyndication.com/pagead/show_ads.js"><\/script>')
        },
        Pn = function() {
            window.dclk_language && (window.google_language =
                window.dclk_language);
            window.google_ad_client = "pub-6219811747049371";
            window.google_ad_channel = "1802068507";
            window.google_ad_format = "300x250_as";
            window.google_ad_type = "text_image";
            window.google_ad_width = 300;
            window.google_ad_height = 250;
            window.google_alternate_color = "FFFFFF";
            window.google_color_border = "FFFFFF";
            window.google_color_bg = "FFFFFF";
            window.google_color_link = "0033CC";
            window.google_color_text = "444444";
            window.google_color_url = "0033CC";
            document.write('<script language="JavaScript" src="//pagead2.googlesyndication.com/pagead/show_ads.js"><\/script>')
        },
        Rn = function() {
            window.ppv_fallback_rendered || (Qn(), V("pyv-placeholder"), U(window.ppv_fallback_placeholder_id || "ppv-placeholder"), window.ppv_fallback_rendered = j)
        },
        Qn = function() {
            V(window.pyv_google_ad_collapse_id || "ad_creative_2")
        },
        Sn = function(a, b, c, d, e) {
            var f = Ya(b.media_template_data, function(a) {
                return !!a.imageUrl
            });
            f && (a = {
                    video_id: f.videoId,
                    ad_type: a,
                    headline: Ca(b.line1),
                    image_url: f.imageUrl,
                    description1: Ca(b.line2),
                    description2: Ca(b.line3),
                    channel_title: f.channelName,
                    test_mode: (!!e).toString(),
                    destination_url: Ca(b.url)
                },
                jg("/pyv?" + ne(a), {
                    method: "GET",
                    update: c,
                    onComplete: d
                }))
        },
        Tn = function() {
            var a = F("ppv-container");
            a && U(a)
        },
        Vn = function() {
            V("ad_creative_2");
            L("PYV_IS_ALLOWED") ? On("ca-youtube-homepage", L("PYV_AD_CHANNEL") || "", L("PYV_KW") || "", Un) : Rn()
        },
        Un = function(a) {
            var b = F("pyv-placeholder");
            0 == a.length || !b ? Rn() : Sn("homepage", a[0], b, function() {
                Qn()
            })
        },
        Xn = function() {
            if (L("PYV_IS_ALLOWED")) {
                var a = "pyvOnBrowse";
                L("PYV_CATEGORY") && (a += " pyvBrowse_" + L("PYV_CATEGORY"));
                On("ca-youtube-browse", a, "", Wn)
            } else Pn()
        },
        Wn = function(a) {
            var b =
                F("pyv-placeholder");
            0 == a.length || !b ? Pn() : Sn(L("PYV_NEW_BROWSE") ? "new_browse" : "browse", a[0], b, function() {
                V("ad_creative_1")
            })
        },
        Yn = function(a, b) {
            var c = F(a);
            c && (c = H("li", "video-list-item", c), u(c, function(a, c) {
                var f = H("a", l, a);
                u(f, function(a) {
                    var d = a.getAttribute("href");
                    d && unescape(d).match(/\/watch(\?|#!)v=/) && (a.href = b ? a.href + ("&pvpos=" + c) : a.href + ("&pvnpos=" + c))
                })
            }))
        };
    var $n = function(a) {
            a = a.replace(";dc_seed=", ";kmyd=watch-channel-brand-div;dc_seed=");
            V("instream_google_companion_ad_div");
            V("google_companion_ad_div");
            U("ad300x250");
            U("watch-channel-brand-div");
            var b = F("ad300x250");
            if (b !== l) {
                var c = Math.round(1E4 * Math.random());
                b.innerHTML = ['<iframe src="', a, '" name="ifr_300x250ad', c, '" id="ifr_300x250ad', c, '" width="300" height="250" marginwidth="0" marginheight="0" hspace="0" vspace="0" frameborder="0" scrolling="no"></iframe>'].join("")
            }
            Zn()
        },
        ao = function(a) {
            a =
                a.replace(";dc_seed=", ";kmyd=watch-longform-ad;dc_seed=");
            V("instream_google_companion_ad_div");
            U("watch-longform-ad");
            U("watch-longform-text");
            U("watch-longform-ad-placeholder");
            var b = F("watch-longform-ad-placeholder"),
                c = Math.round(1E4 * Math.random());
            b.innerHTML = ['<iframe src="', a, '" name="ifr_300x60ad', c, '" id="ifr_300x60ad', c, '" width="300" height="60" marginwidth="0" marginheight="0" hspace="0" vspace="0" frameborder="0" scrolling="no"></iframe>'].join("");
            Zn()
        },
        bo = function(a) {
            var b = F("watch-longform-ad-placeholder");
            a ? (V("instream_google_companion_ad_div"), U("watch-longform-ad"), U("watch-longform-text"), U("watch-longform-ad-placeholder"), b.innerHTML = a) : V("watch-longform-ad");
            Zn()
        },
        co = function(a, b) {
            var c = "watch-channel-brand-div",
                d = "ad300x250",
                e = 300,
                f = 250;
            "video" == a && (c = "watch-longform-ad", d = "watch-longform-ad-placeholder", e = 300, f = 60, V("instream_google_companion_ad_div"));
            var g = decodeURIComponent(b);
            F(d).innerHTML = ['<iframe name="fw_ad" id="fw_ad" ', 'width="' + e + '" height="' + f + '" ', 'marginwidth="0" marginheight="0" hspace="0" vspace="0" frameborder="0" scrolling="no"></iframe>'].join("");
            var k = F("fw_ad"),
                k = k.contentWindow ? k.contentWindow : k.contentDocument.document ? k.contentDocument.document : k.contentDocument,
                e = navigator.userAgent.toLowerCase(),
                d = -1 != e.indexOf("msie"),
                e = -1 != e.indexOf("opera");
            k.document.open();
            k.document.write(g);
            d || e ? M(function() {
                k.document.close()
            }, 7500) : k.document.close();
            U(c);
            Zn()
        },
        eo = function() {
            U("watch-channel-brand-div");
            V("ad300x250");
            F("google_companion_ad_div").style.height = "250px";
            Zn()
        },
        fo = function() {
            V("watch-longform-ad");
            Zn()
        },
        go = function() {
            V("watch-channel-brand-div");
            Zn()
        },
        Zn = function() {
            var a = q("yt.www.watch.ads.handleAdLoaded");
            a && a.call()
        },
        ho = function(a) {
            md("POPOUT_AD_SLOTS", a)
        },
        io = function() {
            var a = F("watch-longform-popup");
            a && (a.disabled = j)
        },
        jo = function(a) {
            var b = F("watch-longform-popup");
            b && (b.disabled = m);
            ho(a)
        },
        ko = function(a) {
            window.google_ad_output = "html";
            a ? (window.google_ad_height = "60", window.google_ad_format = "300x60_as", window.google_container_id = "instream_google_companion_ad_div") : (window.google_ad_height = "250", window.google_ad_format = "300x250_as", window.google_container_id =
                "google_companion_ad_div")
        },
        lo = function(a) {
            a ? (V("watch-longform-ad-placeholder"), V("watch-channel-brand-div"), U("watch-longform-text"), U("watch-longform-ad"), U("instream_google_companion_ad_div")) : (V("ad300x250"), V("watch-longform-ad"), U("google_companion_ad_div"), U("watch-channel-brand-div"));
            Zn()
        },
        mo = function() {
            V("instream_google_companion_ad_div");
            V("watch-longform-text");
            V("watch-longform-ad-placeholder");
            Zn()
        };
    s("yt.www.watch.ads.showForcedMpu", function(a) {
        F("ad300x250").innerHTML = ['<iframe src="', a, '" name="ifr_300x250ad" id="ifr_300x250ad" width="300" height="250" marginwidth="0" marginheight="0" hspace="0" vspace="0" frameborder="0" scrolling="no"></iframe>'].join("");
        U("ad300x250")
    });
    s("yt.www.watch.ads.handleMoveGutCompanion", function(a) {
        var a = a ? F("instream_google_companion_ad_div") : F("google_companion_ad_div"),
            b = F("yt-gut-content");
        b ? b.innerHTML = "" : (b = document.createElement("div"), b.id = "yt-gut-content");
        a && (a.innerHTML = "", a.appendChild(b));
        Zn()
    });
    s("yt.www.watch.ads.setGutSlotSizes", function(a, b) {
        var c = L("gut_slot");
        c && (c = c.getSizes(), ab(c), b && c.push(L("yt_gut_invideo_size")), a && c.push(L("yt_gut_instream_size")))
    });
    s("yt.www.watch.ads.handleSetCompanion", $n);
    s("yt.www.watch.ads.handleSetCompanionForInstream", ao);
    s("yt.www.watch.ads.handleSetCompanionForLongform", bo);
    s("yt.www.watch.ads.handleSetCompanionForFreewheel", co);
    s("yt.www.watch.ads.handleHideCompanion", eo);
    s("yt.www.watch.ads.handleHideCompanionForInstream", fo);
    s("yt.www.watch.ads.disablePopoutButton", io);
    s("yt.www.watch.ads.enablePopoutButton", jo);
    s("yt.www.watch.ads.handleCloseMpuCompanion", go);
    s("yt.www.watch.ads.updatePopoutAds", ho);
    s("yt.www.watch.ads.handleSetAfvCompanionVars", ko);
    s("yt.www.watch.ads.handleShowAfvCompanionAdDiv", lo);
    s("yt.www.watch.ads.handleHideAfvInstreamCompanionAdDiv", mo);
    s("yt.www.ads.pyv.pyvWatchAfcCallback", function(a) {
        if (0 == a.length) Tn(), L("PYV_TRACK_RELATED_CTR") && (Yn("watch-related-grid", m), Yn("watch-related", m), Yn("watch-channel-videos-panel", m));
        else {
            var b = F("watch-channel-videos-panel");
            b && !L("IS_BRANDED_WATCH") && w(b, "yt-uix-expander-collapsed");
            Sn("watch_related", a[0], l, function(a) {
                var a = ig(gg(a.responseXML), "html_content") || "",
                    b = F(window.pyv_related_box_id || "watch-related");
                b && 0 != b.innerHTML.indexOf(a) && (b.insertBefore(vc(a), b.firstChild), L("PYV_TRACK_RELATED_CTR") &&
                    (Yn("watch-related", j), Yn("watch-channel-videos-panel", j)));
                if ((b = F("watch-related-grid")) && 0 != b.innerHTML.indexOf(a)) b.insertBefore(vc(a), b.firstChild), L("PYV_TRACK_RELATED_CTR") && Yn("watch-related-grid", j)
            }, window.google_adtest && "on" == window.google_adtest)
        }
    });
    s("yt.www.ads.pyv.pyvSearchAfcCallback", function(a) {
        var b = F("pyv-ads");
        0 != a.length && b && (u(a, function(a) {
            var b = a.media_template_data[0];
            a.line1 = Ca(a.line1);
            a.line2 = Ca(a.line2);
            a.line3 = Ca(a.line3);
            a.url = Ca(a.url);
            b.imageUrl = Ca(b.imageUrl);
            b.channelName = Ca(b.channelName)
        }), a = {
            pyv_ads: Zf(new Xf(i), a),
            ad_type: "search"
        }, jg("/pyv", {
            method: "POST",
            postBody: ne(a),
            update: b
        }))
    });
    s("yt.www.ads.pyv.pyvHomeAfcCallback", Un);
    s("yt.www.ads.pyv.showPpvAdInYvaSpot", Rn);
    s("yt.www.ads.pyv.pyvHomeRequestAds", Vn);
    s("yt.www.ads.pyv.pyvBrowseRequestAds", Xn);
    s("yt.www.ads.pyv.showPpvOnWatch", Tn);
    s("yt.www.ads.pyv.loadPyvIframe", function(a) {
        var b = window.location.href;
        b.indexOf("#") == b.length - 1 && Ob && (window.location.hash = "#!");
        var b = document.body,
            c = hc(b),
            d = [];
        d.push("<!DOCTYPE html>");
        d.push("<html><head>", a, "</head><body>", i, "</body></html>");
        a = c.Zh("iframe", {
            frameborder: 0,
            style: "border:0;vertical-align:bottom;",
            src: 'javascript:""'
        });
        b.appendChild(a);
        b = d.join("");
        a = a.contentDocument || a.contentWindow.document;
        a.open();
        a.write(b);
        a.close()
    });
    s("yt.www.ads.pyv.loadPyvAfsScript", function(a) {
        qg("/pyv_afs_ads?query=" + a)
    });
    nd("pyv_related_box_id");
    s("setCompanion", $n);
    s("setInstreamCompanion", ao);
    s("setLongformCompanion", bo);
    s("setFreewheelCompanion", co);
    s("closeInPageAdIframe", eo);
    s("hideInstreamCompanion", fo);
    s("disablePopout", io);
    s("enablePopout", jo);
    s("closeMpuCompanion", go);
    s("updatePopAds", ho);
    s("setAfvCompanionVars", ko);
    s("showAfvCompanionAdDiv", lo);
    s("hideAfvInstreamCompanionAdDiv", mo);
    s("show_ppv_in_yva_spot", Rn);
    s("requestPyvAds", Vn);
    s("pyvHomeRequestAds", Vn);
    s("pyvBrowseRequestAds", Xn);
    window.yt = window.yt || {};
    s("_gel", F);
    s("_hasclass", z);
    s("_addclass", w);
    s("_removeclass", x);
    s("_toggleclass", pb);
    s("_showdiv", U);
    s("_hidediv", V);
    s("_ajax", eg);
    s("goog.bind", r);
    s("goog.dom.getElementsByTagNameAndClass", function(a, b, c) {
        return H(a, b, c)
    });
    s("goog.dom.getFirstElementChild", Bc);
    s("goog.array.forEach", u);
    s("goog.array.indexOf", Sa);
    s("goog.array.contains", $a);
    s("yt.dom.hasAncestor", Cd);
    s("yt.setConfig", md);
    s("yt.getConfig", L);
    s("yt.registerGlobal", nd);
    s("yt.setTimeout", M);
    s("yt.setInterval", od);
    s("yt.clearTimeout", pd);
    s("yt.clearInterval", qd);
    s("yt.setMsg", function(a) {
        ld(id, arguments)
    });
    s("yt.getMsg", N);
    s("yt.events.listen", Q);
    s("yt.events.unlisten", Rd);
    s("yt.events.stopPropagation", Td);
    s("yt.events.preventDefault", Ud);
    s("yt.events.getTarget", Sd);
    s("yt.events.clear", function() {
        for (var a in Jd) Md(a)
    });
    s("yt.events.Event", Id);
    Id.prototype.preventDefault = Id.prototype.preventDefault;
    Id.prototype.stopPropagation = Id.prototype.stopPropagation;
    s("yt.pubsub.subscribe", Me);
    s("yt.pubsub.unsubscribeByKey", function(a) {
        var b = q("yt.pubsub.instance_");
        return b ? b.unsubscribeByKey(a) : m
    });
    s("yt.pubsub.publish", Ne);
    s("yt.www.init", Jn);
    s("yt.www.dispose", Kn);
    Q(window, "load", Jn);
    Q(window, "unload", Kn);
    s("yt.www.logError", Mn);
    window.onerror = Mn;
    s("goog.i18n.bidi.isRtlText", fd);
    s("goog.i18n.bidi.setDirAttribute", function(a, b) {
        var c = b.value,
            d = "";
        fd(c) ? d = "rtl" : fd(c) || (d = "ltr");
        b.dir = d
    });
    s("yt.i18n.virtualkeyboard.toggle", function(a, b) {
        a && (Sf = a, Lf("GOOGLE_LANGUAGE_API_VIRTUAL_KEYBOARD", function() {
            if (Rf) {
                var a = Rf;
                a.isVisible() ? a.setVisible(m) : a.setVisible(j)
            } else Tf || (Tf = j, Uf = b, google.load("elements", "1", {
                packages: "keyboard",
                callback: "yt.i18n.virtualkeyboard.finishLoading"
            }))
        }))
    });
    s("yt.i18n.virtualkeyboard.finishLoading", function() {
        google.elements.keyboard.setAutoHide(m);
        google.elements.keyboard.setSendShiftKeyUpEventUponInput(j);
        google.elements.keyboard.enableMinMaxMode(m);
        google.elements.keyboard.enableGoogleLogo(m);
        google.elements.keyboard.enableKeyCodeScheme(m);
        google.elements.keyboard.helpLink(["http://www.google.com/support/youtube/bin/answer.py?answer=1047939&hl=", Uf].join(""));
        Rf = new google.elements.keyboard.Keyboard(Sf, ["masthead-search-term"]);
        Tf = m
    });
    s("yt.i18n.inputtools.loadInputTools", function(a, b, c) {
        a && b && (Mf = a, Lf("GOOGLE_LANGUAGE_API_INPUT_TOOLS", function() {
            !Of && !Pf && (Pf = j, Nf = b, Qf = c, google.load("elements", "1", {
                packages: "inputtools",
                callback: "yt.i18n.inputtools.finishLoading"
            }))
        }))
    });
    s("yt.i18n.inputtools.finishLoading", function() {
        Of = new google.elements.inputtools.InputToolsController;
        tb(Nf, function(a, c) {
            Of.addPageElements([c])
        });
        Of.addInputTools(Mf);
        Qf && -1 != Qf.indexOf("hi") && Of.setCurrentInputTool("im_t13n_hi");
        tb(Nf, function(a) {
            a && Of.showControl(a)
        });
        var a = m;
        u(Mf, function(b) {
            0 == b.lastIndexOf("vdk", 0) && (a = j)
        });
        a && google.elements.keyboard.helpLink(["http://www.google.com/support/youtube/bin/answer.py?answer=1047939&hl=", Qf].join(""));
        Of.setApplicationName("youtube");
        Pf = m
    });
    s("yt.style.toggle", Mg);
    s("yt.style.setDisplayed", Jg);
    s("yt.style.isDisplayed", Kg);
    s("yt.style.setVisible", function(a, b) {
        if (a = F(a)) a.style.visibility = b ? "visible" : "hidden"
    });
    s("yt.net.ajax.sendRequest", jg);
    s("yt.net.ajax.getRootNode", gg);
    s("yt.net.ajax.getNodeValue", ig);
    s("yt.net.ajax.setToken", function(a, b) {
        T[a] = b
    });
    s("yt.net.delayed.register", mg);
    s("yt.net.delayed.load", og);
    s("yt.net.delayed.markAsLoaded", function(a) {
        a in kg && (lg[a] = j)
    });
    s("yt.net.scriptloader.load", qg);
    s("goog.dom.forms.getFormDataString", $c);
    s("yt.uri.buildQueryData", ne);
    s("yt.uri.appendQueryData", re);
    s("yt.www.feedback.start", function(a, b, c, d) {
        try {
            var c = (c || "59") + "",
                b = b || {},
                e = L("SESSION_INDEX");
            try {
                var f = Rj();
                f && f.pauseVideo();
                var g = ze.getInstance();
                b.flashVersion = [g.F, g.qa, g.rev].join(".")
            } catch (k) {}
            a = {
                productId: c,
                locale: a
            };
            e && (a.authuser = e + "");
            d && (a.bucket = d);
            en(a, b);
            return m
        } catch (o) {
            return j
        }
    });
    s("yt.www.feedback.displayLink", Cn);
    Me("init", Cn);
    s("yt.www.help.bootstrap.init", En);
    Me("init", En);
    s("yt.net.cookies.set", $d);
    s("yt.net.cookies.get", ae);
    s("yt.net.cookies.remove", be);
    s("yt.window.redirect", Rh);
    s("yt.window.popup", Sh);
    fh(ih);
    fh(sh);
    fh(yh);
    fh(Bh);
    fh(zh);
    fh(Ch);
    fh(Eh);
    fh(Fh);
    fh(Gh);
    fh(Th);
    fh(Uh);
    s("onYouTubePlayerReady", function() {
        Cm = j;
        var a = Rj();
        a && Mm(a)
    });
    s("handleWatchPagePlayerStateChange", function(a) {
        Dm = a;
        switch (a) {
            case 0:
                pj(Ej()) && Ej().next(m, "autoplay")
        }
    });
    nd("onYouTubePlayerReady", "handleWatchPagePlayerStateChange");
    s("yt.www.watch.activity.getTimeSinceActive", xl);
    s("yt.www.watch.activity.setTimestamp", wl);
    s("yt.www.watch.player.handleEndPreview", function() {
        var a = Rj(),
            b = F("watch-checkout-button");
        a && a.stopVideo && a.stopVideo();
        b ? b.click() : F("rental-gadget") ? Uj() : F("rental-overlay-target") && (a = F("rental-overlay-target"), Eh.getInstance().show(a))
    });
    s("yt.www.watch.player.onPlayerSettingsChanged", function(a) {
        md("PREFER_LOW_QUALITY", a)
    });
    s("yt.www.watch.player.onPlayerSizeClicked", function(a) {
        L("FULLSCREEN_EXPAND") ? A(document.body, "fullscreen", a) : (a ? $d("wide", "1") : $d("wide", "0"), Sj(a))
    });
    s("yt.www.watch.player.onPlayerNextClicked", function() {
        Ej().next(j, i)
    });
    s("yt.www.watch.player.onVolumeChange", function(a) {
        var b = Hm;
        if (b.Za) {
            var c = {};
            c.volume = isNaN(a.volume) ? b.getVolume().volume : Math.min(Math.max(a.volume, 0), 100);
            c.muted = a.muted == i ? b.getVolume().muted : a.muted;
            try {
                b.Za.set("yt-player-volume", c)
            } catch (d) {}
        }
    });
    s("yt.www.watch.player.openPopup", function(a, b, c) {
        var d = l,
            e = Rj(),
            a = "/watch_popup?v=" + a;
        e && (a += "&vq=" + e.getPlaybackQuality(), d = Math.round(e.getCurrentTime()), e.stopVideo());
        L("POPOUT_AD_SLOTS") && (a += "&pop_ads=" + L("POPOUT_AD_SLOTS"));
        d && 10 < d && (a += "#t=" + d);
        Sh(a, {
            width: b,
            height: c,
            resizable: j,
            location: m,
            statusbar: m,
            menubar: m,
            scrollbars: m,
            toolbar: m
        })
    });
    s("yt.www.watch.player.onPlayerShareClicked", function() {
        bl(F("watch-share"), j)
    });
    s("yt.www.watch.activity.init", vl);
    s("yt.www.watch.player.updateConfig", function(a) {
        var a = a instanceof xe ? a.args : a.args,
            b;
        var c = qe(window.location.hash);
        (b = c.t || c.at) ? (Fm.t = c.t, Fm.at = c.at, c = window.location.hash.replace(/\bat=[^&]*&?/, ""), window.location.hash = c && "#" != c ? c : "#!", b = Pm(b)) : b = 0;
        return b ? (a.start = b, j) : m
    });
    s("yt.www.watch.player.init", function() {
        Qm();
        od(Qm, 1E3);
        L("ENABLE_GPU_PREWARMING") && Q(window, "beforeunload", Rm)
    });
    s("yt.www.watch.player.seekTo", Om);
    s("openFull", function() {
        Sh("/watch_popup?v=" + L("VIDEO_ID"), {
            target: "FullScreenVideo",
            width: screen.availWidth,
            height: screen.availHeight,
            resizable: j,
            fullscreen: j
        })
    });
    s("checkCurrentVideo", function(a, b, c) {
        var d = L("VIDEO_ID"),
            e = d == a,
            f = Hj ? Ej().va() : l,
            b = !b || b == f;
        if (d && (!e || !b)) d = l, b && (d = Ej(), e = Ti(d.b, [a])[0], d = e === i ? "" : Wi(d.b, e)), d || (d = c ? c : se(window.location.href, {
            v: a,
            feature: l
        })), d && Rh(d)
    });
    s("trackAnnotationsEvent", function(a, b, c) {
        var d = L("ANALYTICS_ANNOTATIONS_TRACKER");
        window._gaq.push(function() {
            d._trackEvent(a, b, c)
        })
    });
    s("reportFlashTiming", function() {});
    s("reportTimingMaps", function(a, b) {
        for (var c in a) W.jc(c, a[c]);
        for (var d in b) W.info(d, b[d]);
        W.xd()
    });
    s("yt.www.watch.playlists.editAnnotation", function() {
        y(F("watch-video-annotation-editable"), "not-editing", "editing");
        F("watch-video-annotation-textarea").focus();
        var a = F("watch-video-annotation-form");
        P(a, "setup") || (O(a, "setup", "true"), a = I("cancel-button", a), Q(a, "click", function(a) {
            a.preventDefault();
            an()
        }))
    });
    s("yt.www.watch.playlists.removeAnnotation", function() {
        var a = F("watch-video-annotation-form");
        F("watch-video-annotation-textarea").value = "";
        bn(a)
    });
    s("yt.www.watch.playlists.submitForm", function(a) {
        bn(a)
    });
    s("yt.www.watch.abandonment.init", function(a, b) {
        Tm = a;
        Um = b;
        var c = L("PLAYER_CONFIG"),
            c = new xe(c);
        Ie(function(a) {
            var e = W.timer || {};
            e.start && a.isSupported(c.minVersion) && (Wm = e.start, Cm ? Xm(Rj()) : Gm.Vb("READY_STATE_TOPIC", Xm), Q(window, "beforeunload", $m), a = oa() - Wm, a = b - a, 0 <= a && (Vm = M(Zm, a), Ym("attempt")));
            Sm = j
        })
    });
    s("yt.www.watch.abandonment.onPlayerStateChange", function(a) {
        if (Vm) switch (a) {
            case 1:
                Ym("play");
                pd(Vm);
                Vm = l;
                Sm = m;
                break;
            case 0:
                Ym("ended"), pd(Vm), Vm = l, Sm = m
        }
    });
    s("yt.www.watch.abandonment.onError", function() {
        Vm && (Ym("error"), pd(Vm), Vm = l, Sm = m)
    });
    s("yt.history.enable", function(a, b) {
        var c = Ef(b);
        c.setEnabled.call(c, j, a)
    });
    s("yt.history.disable", function() {
        var a = Ef();
        a.setEnabled.call(a, m)
    });
    s("yt.flash.embed", function(a, b) {
        a = F(a);
        b instanceof xe || (b = new xe(b));
        if (window != window.top) {
            var c = l;
            document.referrer && (c = document.referrer.substring(0, 128));
            b.args.framer = c
        }
        Ie(function(c) {
            c.isSupported(b.minVersion) || L("IS_OPERA_MINI") ? (c = Ce(c) && b.url || De(c) && b.urlV9As2 || b.urlV8 || b.url, Ge(a, c, b)) : rd && c.isSupported(6, 0, 65) ? (c = new xe({
                    url: "//s.ytimg.com/yt/swf/expressInstall-vflIE9HEf.swf",
                    args: {
                        MMredirectURL: window.location,
                        MMplayerType: "ActiveX",
                        MMdoctitle: document.title
                    }
                }), Ge(a, c.url, c)) : 0 == c.F && b.fallback ? b.fallback() :
                0 == c.F && b.fallbackMessage ? b.fallbackMessage() : a.innerHTML = '<div id="flash-upgrade">' + N("FLASH_UPGRADE") + "</div>"
        })
    });
    s("yt.flash.update", He);
    s("yt.www.lists.addto.toggleMenu", function(a, b) {
        var c = Uh.getInstance(),
            d = Y(c);
        if (b) {
            var e = F("shared-addto-menu"),
                f = I("addto-menu", e);
            Wh(c, a);
            gl = x(a, d);
            fl || (fl = f.innerHTML);
            c = P(e, "video-ids");
            d = P(a, "video-ids");
            hl && w(e, "lightweight-panel");
            d && c != d && (O(e, "video-ids", d), f.innerHTML = fl, new il(a))
        } else gl && w(a, d)
    });
    s("yt.www.lists.data.addto.saveToWatchLater", function(a, b) {
        Nj(a, b, function(b, d) {
            var e = d.list_id || "",
                f = [a],
                g = N("PLAYLIST_BAR_ADDED_TO_PLAYLIST");
            Jj("WL", e, f, g)
        })
    });
    s("yt.www.watch.watch5.enableWide", Sj);
    s("yt.www.watch.watch5.handleLoadMoreRelated", function() {
        V("watch-more-related-button");
        U("watch-more-related", "watch-more-related-loading");
        S(L("MORE_RELATED_SERVLET"), {
            g: {
                video_id: L("VIDEO_ID"),
                action_more_related_videos: 1
            },
            f: function(a, b) {
                var c = F("watch-more-related");
                c.innerHTML = b.html;
                li(c)
            }
        })
    });
    s("yt.www.watch.watch5.handleYouTubeVJ", function() {
        F("youtube-vj-button").disabled = j;
        S("/music_ajax", {
            g: {
                video_id: L("VIDEO_ID"),
                action_get_vj: 1
            },
            f: function(a, b) {
                if (b.length) {
                    b.unshift(L("VIDEO_ID"));
                    var c = Ej();
                    Ki([]);
                    Li(b);
                    var d = new ej;
                    oj(c, d);
                    d.C = 0;
                    qj(c, j)
                }
            }
        })
    });
    s("yt.www.watch.watch5.handleToggleMoreFromUser", function(a) {
        var b = z(a, "yt-uix-expander-collapsed"),
            c = F("watch-more-from-user");
        !b && "true" != P(c, "loaded") && (a = {
            user_id: P(a, "video-user-id"),
            video_id: P(a, "video-id"),
            action_channel_videos: "1"
        }, S("/watch_ajax", {
            format: "XML",
            method: "GET",
            g: a,
            f: function(a, b) {
                c.innerHTML = b.html_content;
                O(c, "loaded", "true");
                li(c);
                var f = parseInt(P(F("watch-channel-discoverbox"), "slider-slide-selected"), 10);
                H("button", "yt-uix-slider-num", i)[f].click()
            }
        }));
        A(c, "collapsed", b)
    });
    s("yt.www.watch.watch5.handleToggleDescription", function(a) {
        if (z(a, "yt-uix-expander-collapsed")) X("descriptionClosed", i, i);
        else {
            if ((a = F("watch-source-videos-list")) && "true" != P(a, "loaded")) {
                O(a, "loaded", "true");
                var b = re("/watch_ajax", {
                    action_get_video_attributions_component: 1,
                    v: L("VIDEO_ID")
                });
                jg(b, {
                    method: "GET",
                    update: a
                })
            }
            X("descriptionOpened", i, i)
        }
    });
    s("yt.www.watch.watch5.purchaseComplete", function() {
        U(F("watch-player-rental-play-button"));
        Uj()
    });
    s("yt.www.watch.actions.init", function() {
        var a = qe(window.location.hash),
            b = a.action;
        if (b) {
            switch (b) {
                case "flag":
                    $k()
            }
            M(function() {
                al("watch-actions-area-container").scrollIntoView()
            }, 0);
            delete a.action;
            a = ne(a) || "#!";
            window.location.hash = a
        }
    });
    s("yt.www.watch.shortcuts.init", function() {
        Q(document, "keypress", cn)
    });
    s("yt.www.watch.actions.captions", function(a) {
        Zk(a) && (Vk(), Kk(function() {
            V("watch-actions-loading");
            Tk("watch-actions-captions")
        }))
    });
    s("yt.www.watch.actions.flag", $k);
    s("yt.www.watch.actions.hide", function() {
        Uk()
    });
    s("yt.www.watch.actions.like", function(a) {
        Zk(a) && Yk(j)
    });
    s("yt.www.watch.actions.share", bl);
    s("yt.www.watch.actions.showSigninOrCreateChannelWarning", function(a) {
        Zk(a) && Tk("watch-actions-logged-out")
    });
    s("yt.www.watch.actions.stats", function(a) {
        Zk(a) && (Vk(), S("/insight_ajax", {
            format: "XML",
            method: "GET",
            g: {
                action_get_statistics_and_data: 1,
                v: L("VIDEO_ID")
            },
            f: function(a, c) {
                Wk(c.html_content)
            },
            r: Xk
        }))
    });
    s("yt.www.watch.actions.unlike", function(a) {
        Zk(a) && Yk(m)
    });
    s("yt.www.watch.watch5.showIE9WebMPromo", function(a) {
        var b = document.createElement("video");
        b.canPlayType && !b.canPlayType("video/webm") && U(a)
    });
    s("yt.www.watch.watch5.getMoviePlayer", Rj);
    s("yt.www.watch.watch5.showFlashUpgradePromo", function(a, b) {
        Ie(function(c) {
            if (!c.isSupported(b[0], b[1], b[2])) {
                U(a);
                var c = R.getInstance(),
                    d = parseInt(c.get("ftuc") || 0, 10) + 1;
                c.set("ftuc", d);
                c.save()
            }
        })
    });
    s("yt.www.comments.init", function() {
        var a = F("comments-view");
        new ti(a);
        Qd(a, "click", Ei, "comment-action")
    });
    s("yt.www.comments.initForm", function(a, b) {
        var c = K(a, "form");
        if (!(c.dataset ? ud("initialized") in c.dataset : c.hasAttribute ? c.hasAttribute("data-initialized") : c.getAttribute("data-initialized"))) O(c, "initialized", "true"), c = new pi(c, !b), b && c.focus()
    });
    s("yt.www.lists.init", function() {
        if (-1 < parseInt(L("PLAYLIST_BAR_PLAYING_INDEX"), 10)) {
            Ej();
            var a = L("AUTOPLAY_DELAY");
            a && Ij(a)
        }
        Hj = j
    });
    s("yt.www.lists.getState", function() {
        var a = Fj();
        a.autoPlayMax = L("PLAY_ALL_MAX");
        return a
    });
    s("yt.www.lists.registerNearEndEventsWithPlayer", Kj);
    s("yt.www.lists.handleNearPlaybackEnd", function(a) {
        Hj && pj(Ej()) && "NEAR_END" == a.slice(0, -1) && (a = parseInt(a.slice(-1), 10), Aj(Ej(), a))
    });
    s("yt.dom.datasets.get", P);
    s("yt.dom.datasets.set", O);
    s("yt.www.watch.watch5.reportAProblemForPaidContent", function(a) {
        Ie(function(b) {
            var b = [b.F, b.qa, b.rev].join("."),
                c = Rj(),
                b = re("http://www.google.com/support/youtube/bin/answer.py?answer=1084880", {
                    vid: L("VIDEO_ID"),
                    currentTime: c && c.getCurrentTime ? c.getCurrentTime() : -1,
                    totalTime: c && c.getDuration ? c.getDuration() : -1,
                    quality: c && c.getPlaybackQuality ? c.getPlaybackQuality() : -1,
                    flashVersion: b,
                    plid: a
                });
            Qh(b, {}, window)
        })
    });
    nd("openFull", "checkCurrentVideo", "trackAnnotationsEvent", "reportFlashTiming", "shareVideoFromFlash", "setCompanion", "setInstreamCompanion", "setLongformCompanion", "setFreewheelCompanion", "closeInPageAdIframe", "hideInstreamCompanion", "disablePopout", "enablePopout", "closeMpuCompanion", "updatePopAds", "setAfvCompanionVars", "showAfvCompanionAdDiv", "hideAfvInstreamCompanionAdDiv", "show_ppv_in_yva_spot", "requestPyvAds", "pyvHomeRequestAds", "pyvBrowseRequestAds", "showGutCompanion");
    s("yt.www.search.init", function() {
        Qd(F("search-footer-box"), "mouseover", function() {}, "yt-uix-pager-page-num")
    });
    s("yt.www.search.legos.toggleExpandedRefinements", function() {
        Lg(F("search-lego-refinements"));
        return m
    });
    s("yt.www.search.legos.initLegos", function() {
        Il = F("lego-preview");
        Jl = F("search-header");
        var a = F("lego-refine-block");
        Qd(a, "mouseover", Ml, "lego-content");
        Qd(a, "mouseout", Nl, "lego-content");
        Qd(a, "mouseover", Kl, "lego-action");
        Qd(a, "mouseout", Ll, "lego-action")
    });
    s("yt.www.search.toggleToolbelt", function() {
        var a = F("toolbelt-top"),
            b = F("search-option-expander"),
            c = !z(b, "expanded");
        A(b, "expanded", c);
        c ? (U(a), a.style.height = F("toolbelt-container").offsetHeight + "px") : (a.style.height = 0, M(function() {
            V(a)
        }, 300));
        return m
    });
    s("yt.www.thumbnaildelayload.init", function(a) {
        ci = a || 0;
        bi = gi();
        fi = j;
        mi(i, i);
        ji();
        Q(window, "scroll", ii);
        Q(window, "resize", ii)
    });
    s("yt.www.xsrf.populateSessionToken", function() {
        for (var a = 0; a < document.forms.length; a++) {
            for (var b = m, c = 0; c < Nn.length; c++) document.forms[a].name == Nn[c] && (b = j);
            c = document.forms[a];
            if ("post" == c.method.toLowerCase() && b == m) {
                for (var b = m, d = 0; d < c.elements.length; d++) c.elements[d].name == L("XSRF_FIELD_NAME") && (b = j);
                b || (b = i, b = L("XSRF_TOKEN"), d = document.createElement("input"), d.setAttribute("name", L("XSRF_FIELD_NAME")), d.setAttribute("type", "hidden"), d.setAttribute("value", b), c.appendChild(d))
            }
        }
    });
    s("yt.www.masthead.performSearch", function(a, b) {
        var c = F("masthead-search"),
            d = P(b, a) || "";
        "rentals" == d ? (c.rental.value = 1, c.search_type.value = "") : (c.search_type.value = d, c.rental.value = 0);
        if (c.search_query.value) c.submit();
        else {
            var c = b.innerHTML,
                e = F("search-btn"),
                f = e.innerHTML,
                g = P(e, a) || "";
            e.innerHTML = c;
            O(e, a, d);
            b.innerHTML = f;
            O(b, a, g)
        }
        return m
    });
    s("yt.www.masthead.loadPicker", function(a, b, c) {
        var d = F(a);
        d ? c ? U(d) : Mg(d) : (d = document.createElement("div"), d.id = a, V(d), F("picker-container").appendChild(d), c = "/masthead_ajax?action_get_" + a.replace("-", "_") + "=1", b && (c = b + c), jg(c, {
            method: "GET",
            update: a,
            onComplete: function() {
                V("picker-loading");
                U(a);
                F(a).scrollIntoView()
            }
        }), U("picker-loading"));
        w(d, "yt-tile-static");
        Hl(a);
        F(a).scrollIntoView()
    });
    s("yt.www.masthead.dismissGAPlusMessage", function() {
        var a = ae("FML", "").split(","),
            b = new Date,
            b = Math.round(b.getTime() / 1E3),
            c = "",
            c = 2 != a.length ? "1," + b : parseInt(a[0], 10) + 1 + "," + b;
        $d("FML", c, 31536E4)
    });
    s("yt.www.masthead.dismissPostLinkingMessage", function() {
        be("FML")
    });
    s("yt.www.masthead.toggleExpandedMasthead", function() {
        var a = F("masthead-expanded");
        Lg(a);
        Jg("masthead-expanded-menu", j);
        Jg("masthead-expanded-acct-sw-container", m);
        rl || x(F("masthead-expanded-container"), "accountswitch");
        if (!P(a, "loaded")) {
            var b = F("masthead-expanded-menu-gaia-photo");
            b && !b.src && (b.src = P(b, "src"));
            S("/playlist_bar_ajax", {
                g: {
                    action_get_playlists_masthead: 1,
                    feature: "mhee",
                    new_slider: 1
                },
                format: "JSON",
                Fb: j,
                f: function(b, d) {
                    F("masthead-expanded-lists-container").innerHTML = d.html;
                    O(a, "loaded",
                        "true")
                }
            })
        }
    });
    s("yt.www.masthead.accountswitch.init", function(a) {
        rl = a
    });
    s("yt.www.masthead.accountswitch.toggle", function() {
        Lg("masthead-expanded-acct-sw-container");
        var a = F("masthead-expanded-container"),
            b = F("masthead-expanded-acct-sw-container");
        if (Kg(b)) {
            b.style.top = a.offsetTop + "px";
            b = F("masthead-expanded-menu-acct-sw-list");
            b.offsetHeight < a.offsetHeight && (b.style.height = a.offsetHeight - 11 + "px");
            var c = F("masthead-expanded-acct-sw-iframe");
            if (!c) {
                var d = F("masthead-expanded-menu-acct-sw-list"),
                    c = rc("iframe", {
                        id: "masthead-expanded-acct-sw-iframe",
                        frameborder: 0,
                        src: 'javascript:""'
                    });
                d.parentNode && d.parentNode.insertBefore(c, d)
            }
            c.style.height = b.offsetHeight - 11 + "px";
            rl || w(a, "accountswitch")
        } else rl || x(a, "accountswitch")
    });
    s("yt.www.masthead.initSandbar", function(a, b) {
        window.__loadSandbar || (window.__loadSandbar = function() {
            var c = F("sb-button-notify"),
                d = F("sb-button-share"),
                e = new Cl(a, b);
            Q(c, "click", r(e.Xh, e));
            Q(d, "click", r(e.Yh, e))
        }, qg("https://apis.google.com/js/plusone.js?onload=__loadSandbar&googleapis=1"))
    });
    s("yt.www.ads.MastheadAd", ni);
    ni.prototype.collapse_ad = ni.prototype.collapse;
    ni.prototype.expand_ad = ni.prototype.expand;
    s("yt.www.home.ads.workaroundIE", function(a) {
        !Gi && Fi && (Gi = j, M(function() {
            a.focus()
        }, 0))
    });
    s("yt.www.home.ads.workaroundLoad", function() {
        Fi = j
    });
    s("yt.www.home.ads.workaroundReset", function() {
        Gi = m
    });
    s("yt.www.home.ads.yvaCollapse", function() {
        var a = F("yva-bin");
        if (a) {
            var b = F("yva-bg");
            b && y(b, "yva-360-bg", "yva-250-bg");
            y(a, "yva-expanded", "yva-collapsed")
        }
    });
    s("yt.www.home.ads.yvaExpand", function() {
        var a = F("yva-bin");
        a && y(a, "yva-collapsed", "yva-expanded")
    });
    s("yt.tracking.doubleclick.trackActivity", function(a, b, c) {
        a = ("https:" == document.location.protocol ? "https://" : "http://") + "fls.doubleclick.net/activityi;src=" + ta(L("DBLCLK_ADVERTISER_ID")) + ";type=" + ta(a) + ";cat=" + ta(b);
        c && !c.ord && (a += ";ord=1");
        for (var d in c) a += ";" + ta(d) + "=" + ta(c[d]);
        a += ";num=" + oa();
        c = document.createElement("iframe");
        c.src = a;
        c.style.display = "none";
        document.body.appendChild(c)
    });
    s("yt.tracking.track", function(a, b, c) {
        X(a, b, c)
    });
    s("yt.tracking.resolution", function() {
        Pg("/mac_204?" + ("action_scr2=1&height=" + screen.height + "&width=" + screen.width + "&depth=" + screen.colorDepth), i)
    });
    s("yt.tracking.share", Rg);
    s("yt.tracking.resources.init", function() {
        Tg || (Tg = j, M(Vg, 4E3))
    });
    s("yt.analytics.urchinTracker", function() {});
    s("yt.analytics.trackEvent", td);
    s("yt.timing.report", W.pc);
    s("yt.timing.maybeReport", W.xd);
    s("yt.timing.handlePageLoad", W.ef);
    s("yt.timing.handleThumbnailLoad", W.$h);
    Me("init", W.ef);
    rd && (!document.documentMode || 8 > document.documentMode) && Qd(F("ie"), "click", Tj, "video-thumb");
    s("yt.www.subscriptions.edit.onUpdateSubscription", function(a, b, c, d) {
        var c = c || "",
            e = m;
        (b = F("subscription_level_unsubscribe")) && b.checked && (e = j);
        b = $c(F("subscription_level_uploads" + c).form);
        jg("/ajax_subscriptions?" + b, {
            postBody: "session_token=" + a,
            onComplete: function(a) {
                F("subscribeMessage" + c).innerHTML = ig(gg(a), "html_content");
                V("edit_subscription_wrapper" + c);
                V("edit_subscription_arrow" + c);
                U("subscribeMessage" + c);
                c && (F("edit_subscription_opener" + c).style.visibility = "", M(function() {
                    V("subscribeMessage" +
                        c)
                }, 5E3));
                if (e) {
                    var b = F("channel-body"),
                        a = H("div", "subscribe-div", b),
                        b = H("div", "unsubscribe-div", b);
                    u(a, function(a) {
                        Mg(a)
                    });
                    u(b, function(a) {
                        Mg(a)
                    });
                    d()
                }
            }
        })
    });
    s("yt.www.subscriptions.edit.onCancelUpdateSubscription", function(a) {
        a = a || "";
        V("edit_subscription_wrapper" + a);
        V("edit_subscription_arrow" + a);
        a && (F("edit_subscription_opener" + a).style.visibility = "");
        V("alerts")
    });
    s("yt.www.subscriptions.edit.onEditSubscriptionFromRecentActivity", function(a, b, c, d) {
        window["edit_subscription_download_" + c] ? (V("subscribeMessage" + c), Mg("edit_subscription_wrapper" + c), Mg("edit_subscription_arrow" + c), a = F("edit_subscription_opener" + c), a.style.visibility = "visible" == a.style.visibility ? "" : "visible") : (window["edit_subscription_download_" + c] = j, jg("/ajax_subscriptions?get_edit_subscription_form=" + b + "&i=" + c, {
            postBody: "session_token=" + a,
            onComplete: function(a) {
                F("edit_subscription_opener" + c).style.visibility =
                    "visible";
                var b = document.createElement("div");
                b.innerHTML = ig(a.responseXML, "html_content");
                d.parentNode.insertBefore(b, d);
                U("edit_subscription_wrapper" + c);
                U("edit_subscription_arrow" + c)
            }
        }))
    });
    s("yt.www.subscriptions.SubscriptionButton.init", function(a, b) {
        var c = jc("yt-subscription-button-js-default", a);
        u(c, function(a) {
            P(a, "subscription-initialized") || (new om(a, b), O(a, "subscription-initialized", "true"))
        })
    });
    s("yt.www.subscriptions.button.subscribe", function(a) {
        var b = $l(a),
            c = P(b, "subscription-type"),
            d = P(b, "subscription-xsrf") || "",
            e = P(b, "subscription-menu-type"),
            f = P(b, "subscription-channels-container"),
            g = P(b, "subscription-feature"),
            k = P(b, "subscription-value"),
            o = {};
        "playlist" == c ? (o.action_create_subscription_to_playlist = 1, c = "p") : "blog" == c ? (o.action_create_subscription_to_blog = 1, c = "b") : "topic" == c ? (o.action_create_subscription_to_topic = 1, c = "l") : (o.action_create_subscription_to_user = 1, c = "u");
        g && (o.feature =
            g);
        a.disabled = j;
        Tl(d);
        d = new Rl(jm, nm);
        Sl(d, o);
        o = {};
        o[c] = k;
        o.menu_type = e;
        (e = L("PLAYBACK_ID")) && (o.plid = e);
        f && (o.show_channels = j);
        d.ab = o || {};
        d.userData.eventTrigger = a;
        d.userData.subscription = b;
        Oj("convSubscribeUrl");
        Xl(d)
    });
    s("yt.www.subscriptions.button.subscribeToCollection", function(a) {
        for (var b = em(a), c = K(a, l, "subscription-recommendations"), d = P(b, "subscription-xsrf") || "", e = [], f = H("input", "username", c), g = 0; g < f.length; g++) f[g].checked && e.push(f[g].value);
        0 == e.length ? yc(c) : (a.disabled = j, Tl(d), d = new Rl(km, nm), Sl(d, {
            action_create_subscription_to_users: 1
        }), f = {}, f.usernames = e.join(","), d.ab = f || {}, d.userData.eventTrigger = a, d.userData.subscription = b, d.userData.collection = c, Xl(d))
    });
    s("yt.www.subscriptions.button.unsubscribe", function(a) {
        var b = em(a),
            c = P(b, "subscription-id"),
            d = P(b, "subscription-xsrf") || "";
        if (!a.disabled) {
            a.disabled = j;
            Tl(d);
            d = new Rl(lm, nm);
            Sl(d, {
                action_remove_subscription: 1
            });
            var e = {};
            e.subscription_id = c;
            (c = L("PLAYBACK_ID")) && (e.plid = c);
            d.ab = e || {};
            d.userData.eventTrigger = a;
            d.userData.subscription = b;
            Pj();
            Xl(d)
        }
    });
    s("yt.www.subscriptions.button.update", function(a) {
        var b = em(a),
            c = P(b, "subscription-id"),
            d = P(b, "subscription-xsrf") || "";
        a.disabled = j;
        Tl(d);
        var d = new Rl(mm, nm),
            e = K(a, l, "subscription-menu-form"),
            e = Vc(Yc(e));
        e.email_on_upload || (e.email_on_upload = m);
        e.action_update_subscription_preferences = 1;
        Sl(d, e);
        d.ab = {
            subscription_id: c
        };
        d.userData.eventTrigger = a;
        d.userData.subscription = b;
        Xl(d)
    });
    s("yt.www.subscriptions.button.toggleMenu", bm);
    s("yt.www.subscriptions.button.closeMenu", function(a) {
        a = em(a);
        "button" == P(a, "subscription-menu-type") ? gm(a) : hm(a)
    });
    nd("yt", "goog", "_gel", "googleapisv0", "_hasclass", "_addclass", "_removeclass", "_showdiv", "_hidediv", "_ajax");
    s("yt.www.masthead.extended.redirectWithNewParam", function(a, b) {
        var c, d, e;
        c = window.location.href;
        c = c.split("#");
        d = 2 == c.length ? "#" + c[1] : "";
        c = c[0];
        e = pe(c);
        e[b] = a;
        e["persist_" + b] = "1";
        c = c.split("?");
        c = c[0];
        Rh(c, e, d)
    });
    s("yt.www.watch.stats.extended.setInsightOptOut", function(a) {
        a ? (w(F("insight-private"), "selected"), x(F("insight-public"), "selected")) : (w(F("insight-public"), "selected"), x(F("insight-private"), "selected"));
        var b = F("insight-optout-form");
        b && (a = re(b.action, {
            opt_out: a
        }), b = $c(b), jg(a, {
            postBody: b,
            onComplete: function() {}
        }));
        return m
    });
    s("yt.www.watch.stats.extended.toggleReferrer", function(a) {
        Mg(a);
        return m
    });
    s("yt.www.watch.survey.takeWatchPageSurvey", function() {
        dn();
        window.open("/watch_page_survey?r2=" + L("SURVEY_REFERER") + "&r1=" + L("SURVEY_SERVLET_NAME") + "&name=" + L("SURVEY_TYPE"), "YouTube_User_Happiness_Survey", "toolbar=no,width=800,height=768,status=no,resizable=yes,fullscreen=no,scrollbars=yes").focus()
    });
    s("yt.www.watch.survey.watchPageSurveyGoAway", dn);
    s("yt.www.watch.survey.checkSurveyCompletedAndShow", function() {
        ge(R.getInstance(), ai.$e) || U("watch_page_survey")
    });
    s("yt.www.watch.user.unblockUserLinkByUsername", function(a, b) {
        if (!confirm(N("UNBLOCK_USER"))) return m;
        var c = {
            postBody: "unblock_user=0&" + L("BLOCK_USER_XSRF") + "&friend_username=" + a
        };
        b && (c.onComplete = function() {
            window.location.reload()
        });
        jg("/link_servlet", c);
        return m
    });
    s("yt.www.watch.user.blockUserLinkByUsername", function(a, b) {
        if (!confirm(N("BLOCK_USER"))) return m;
        var c = {
            postBody: "block_user=1&" + L("BLOCK_USER_XSRF") + "&friend_username=" + a
        };
        b && (c.onComplete = function() {
            window.location.reload()
        });
        jg("/link_servlet", c);
        return m
    });
    s("yt.www.watch.user.unblockUserLinkByExternalId", function(a, b) {
        confirm(N("UNBLOCK_USER")) && S("/link_ajax?action_unblock_user=1", {
            format: "XML",
            method: "POST",
            sa: L("BLOCK_USER_AJAX_XSRF") + "&uid=" + a,
            f: function() {
                b && window.location.reload()
            }
        })
    });
    s("yt.www.watch.user.blockUserLinkByExternalId", function(a, b) {
        confirm(N("BLOCK_USER")) && S("/link_ajax?action_block_user=1", {
            format: "XML",
            method: "POST",
            sa: L("BLOCK_USER_AJAX_XSRF") + "&uid=" + a,
            f: function() {
                b && window.location.reload()
            }
        })
    });
    s("getNextVideoId", function(a) {
        var b = Ej(),
            c = pj(b),
            d = a <= L("PLAY_ALL_MAX");
        return c && d ? b.bd(a) : ""
    });
    nd("getNextVideoId");
})();
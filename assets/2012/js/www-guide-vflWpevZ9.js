(function() {
    var h = void 0,
        l = !0,
        n = null,
        o = !1,
        r, t = this,
        u = function(a) {
            for (var a = a.split("."), b = t, c; c = a.shift();)
                if (b[c] != n) b = b[c];
                else return n;
            return b
        },
        aa = function(a) {
            a.getInstance = function() {
                return a.Na || (a.Na = new a)
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
        v = function(a) {
            return a !== h
        },
        ca = function(a) {
            return "array" == ba(a)
        },
        w = function(a) {
            return "string" == typeof a
        },
        da = "closure_uid_" + Math.floor(2147483648 * Math.random()).toString(36),
        ea = 0,
        fa = function(a, b, c) {
            return a.call.apply(a.bind,
                arguments)
        },
        ga = function(a, b, c) {
            if (!a) throw Error();
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
        x = function(a, b, c) {
            x = Function.prototype.bind && -1 != Function.prototype.bind.toString().indexOf("native code") ? fa : ga;
            return x.apply(n, arguments)
        },
        ha = Date.now || function() {
            return +new Date
        },
        y = function(a, b) {
            var c = a.split("."),
                d = t;
            !(c[0] in d) && d.execScript && d.execScript("var " + c[0]);
            for (var e; c.length && (e = c.shift());) !c.length && v(b) ? d[e] = b : d = d[e] ? d[e] : d[e] = {}
        },
        A = function(a, b) {
            function c() {}
            c.prototype = b.prototype;
            a.ia = b.prototype;
            a.prototype = new c
        };
    Function.prototype.bind = Function.prototype.bind || function(a, b) {
        if (1 < arguments.length) {
            var c = Array.prototype.slice.call(arguments, 1);
            c.unshift(this, a);
            return x.apply(n, c)
        }
        return x(this, a)
    };
    var ia = function(a) {
        this.stack = Error().stack || "";
        a && (this.message = "" + a)
    };
    A(ia, Error);
    ia.prototype.name = "CustomError";
    var ja = function(a, b) {
            for (var c = 1; c < arguments.length; c++) var d = ("" + arguments[c]).replace(/\$/g, "$$$$"),
                a = a.replace(/\%s/, d);
            return a
        },
        ka = /^[a-zA-Z0-9\-_.!~*'()]*$/,
        la = function(a) {
            a = "" + a;
            return !ka.test(a) ? encodeURIComponent(a) : a
        },
        ra = function(a) {
            if (!ma.test(a)) return a; - 1 != a.indexOf("&") && (a = a.replace(na, "&amp;")); - 1 != a.indexOf("<") && (a = a.replace(oa, "&lt;")); - 1 != a.indexOf(">") && (a = a.replace(pa, "&gt;")); - 1 != a.indexOf('"') && (a = a.replace(qa, "&quot;"));
            return a
        },
        na = /&/g,
        oa = /</g,
        pa = />/g,
        qa = /\"/g,
        ma = /[&<>\"]/;
    var sa = function(a, b) {
        b.unshift(a);
        ia.call(this, ja.apply(n, b));
        b.shift()
    };
    A(sa, ia);
    sa.prototype.name = "AssertionError";
    var ta = function(a, b, c) {
        if (!a) {
            var d = Array.prototype.slice.call(arguments, 2),
                e = "Assertion failed";
            if (b) var e = e + (": " + b),
                f = d;
            throw new sa("" + e, f || []);
        }
    };
    var B = Array.prototype,
        C = B.indexOf ? function(a, b, c) {
            ta(a.length != n);
            return B.indexOf.call(a, b, c)
        } : function(a, b, c) {
            c = c == n ? 0 : 0 > c ? Math.max(0, a.length + c) : c;
            if (w(a)) return !w(b) || 1 != b.length ? -1 : a.indexOf(b, c);
            for (; c < a.length; c++)
                if (c in a && a[c] === b) return c;
            return -1
        },
        E = B.forEach ? function(a, b, c) {
            ta(a.length != n);
            B.forEach.call(a, b, c)
        } : function(a, b, c) {
            for (var d = a.length, e = w(a) ? a.split("") : a, f = 0; f < d; f++) f in e && b.call(c, e[f], f, a)
        },
        ua = B.filter ? function(a, b, c) {
            ta(a.length != n);
            return B.filter.call(a, b, c)
        } : function(a,
            b, c) {
            for (var d = a.length, e = [], f = 0, g = w(a) ? a.split("") : a, i = 0; i < d; i++)
                if (i in g) {
                    var j = g[i];
                    b.call(c, j, i, a) && (e[f++] = j)
                } return e
        },
        va = B.map ? function(a, b, c) {
            ta(a.length != n);
            return B.map.call(a, b, c)
        } : function(a, b, c) {
            for (var d = a.length, e = Array(d), f = w(a) ? a.split("") : a, g = 0; g < d; g++) g in f && (e[g] = b.call(c, f[g], g, a));
            return e
        },
        wa = function(a, b) {
            var c;
            a: {
                c = a.length;
                for (var d = w(a) ? a.split("") : a, e = 0; e < c; e++)
                    if (e in d && b.call(h, d[e], e, a)) {
                        c = e;
                        break a
                    } c = -1
            }
            return 0 > c ? n : w(a) ? a.charAt(c) : a[c]
        },
        xa = function(a, b) {
            ta(a.length !=
                n);
            B.splice.call(a, b, 1)
        },
        ya = function(a, b) {
            for (var c = 1; c < arguments.length; c++) {
                var d = arguments[c],
                    e, f;
                if (!(f = ca(d))) e = d, f = ba(e), f = (e = "array" == f || "object" == f && "number" == typeof e.length) && d.hasOwnProperty("callee");
                if (f) a.push.apply(a, d);
                else if (e) {
                    f = a.length;
                    for (var g = d.length, i = 0; i < g; i++) a[f + i] = d[i]
                } else a.push(d)
            }
        },
        Ba = function(a, b, c, d) {
            ta(a.length != n);
            B.splice.apply(a, Aa(arguments, 1))
        },
        Aa = function(a, b, c) {
            ta(a.length != n);
            return 2 >= arguments.length ? B.slice.call(a, b) : B.slice.call(a, b, c)
        };
    var Ca, F = function(a) {
            return (a = a.className) && "function" == typeof a.split ? a.split(/\s+/) : []
        },
        Ea = function(a, b) {
            var c = F(a),
                d = Aa(arguments, 1);
            Da(c, d);
            a.className = c.join(" ")
        },
        Ga = function(a, b) {
            var c = F(a),
                d = Aa(arguments, 1);
            Fa(c, d);
            a.className = c.join(" ")
        },
        Da = function(a, b) {
            for (var c = 0, d = 0; d < b.length; d++) 0 <= C(a, b[d]) || (a.push(b[d]), c++)
        },
        Fa = function(a, b) {
            for (var c = 0, d = 0; d < a.length; d++) 0 <= C(b, a[d]) && (Ba(a, d--, 1), c++)
        },
        G = function(a, b, c) {
            c ? Ea(a, b) : Ga(a, b)
        };
    var H = function(a, b) {
        this.x = v(a) ? a : 0;
        this.y = v(b) ? b : 0
    };
    H.prototype.p = function() {
        return new H(this.x, this.y)
    };
    H.prototype.toString = function() {
        return "(" + this.x + ", " + this.y + ")"
    };
    var Ha = function(a, b) {
        return new H(a.x - b.x, a.y - b.y)
    };
    var Ia = function(a, b) {
        this.width = a;
        this.height = b
    };
    r = Ia.prototype;
    r.p = function() {
        return new Ia(this.width, this.height)
    };
    r.toString = function() {
        return "(" + this.width + " x " + this.height + ")"
    };
    r.floor = function() {
        this.width = Math.floor(this.width);
        this.height = Math.floor(this.height);
        return this
    };
    r.round = function() {
        this.width = Math.round(this.width);
        this.height = Math.round(this.height);
        return this
    };
    r.scale = function(a) {
        this.width *= a;
        this.height *= a;
        return this
    };
    var Ka = function(a) {
        var b = Ja,
            c;
        for (c in b)
            if (a.call(h, b[c], c, b)) return c
    };
    var La, Ma, Na, Oa, Pa, Qa, Ra = function() {
            return t.navigator ? t.navigator.userAgent : n
        },
        Sa = function() {
            return t.navigator
        };
    Pa = Oa = Na = Ma = La = o;
    var Ta;
    if (Ta = Ra()) {
        var Ua = Sa();
        La = 0 == Ta.indexOf("Opera");
        Ma = !La && -1 != Ta.indexOf("MSIE");
        Oa = (Na = !La && -1 != Ta.indexOf("WebKit")) && -1 != Ta.indexOf("Mobile");
        Pa = !La && !Na && "Gecko" == Ua.product
    }
    var Va = La,
        I = Ma,
        Wa = Pa,
        J = Na,
        Xa = Oa,
        Ya = Sa();
    Qa = -1 != (Ya && Ya.platform || "").indexOf("Mac");
    var Za = !!Sa() && -1 != (Sa().appVersion || "").indexOf("X11"),
        $a;
    a: {
        var ab = "",
            bb;
        if (Va && t.opera) var cb = t.opera.version,
            ab = "function" == typeof cb ? cb() : cb;
        else if (Wa ? bb = /rv\:([^\);]+)(\)|;)/ : I ? bb = /MSIE\s+([^\);]+)(\)|;)/ : J && (bb = /WebKit\/(\S+)/), bb) var db = bb.exec(Ra()),
            ab = db ? db[1] : "";
        if (I) {
            var eb, fb = t.document;
            eb = fb ? fb.documentMode : h;
            if (eb > parseFloat(ab)) {
                $a = "" + eb;
                break a
            }
        }
        $a = ab
    }
    var gb = $a,
        hb = {},
        ib = function(a) {
            var b;
            if (!(b = hb[a])) {
                b = 0;
                for (var c = ("" + gb).replace(/^[\s\xa0]+|[\s\xa0]+$/g, "").split("."), d = ("" + a).replace(/^[\s\xa0]+|[\s\xa0]+$/g, "").split("."), e = Math.max(c.length, d.length), f = 0; 0 == b && f < e; f++) {
                    var g = c[f] || "",
                        i = d[f] || "",
                        j = RegExp("(\\d*)(\\D*)", "g"),
                        m = RegExp("(\\d*)(\\D*)", "g");
                    do {
                        var k = j.exec(g) || ["", "", ""],
                            p = m.exec(i) || ["", "", ""];
                        if (0 == k[0].length && 0 == p[0].length) break;
                        b = ((0 == k[1].length ? 0 : parseInt(k[1], 10)) < (0 == p[1].length ? 0 : parseInt(p[1], 10)) ? -1 : (0 == k[1].length ?
                            0 : parseInt(k[1], 10)) > (0 == p[1].length ? 0 : parseInt(p[1], 10)) ? 1 : 0) || ((0 == k[2].length) < (0 == p[2].length) ? -1 : (0 == k[2].length) > (0 == p[2].length) ? 1 : 0) || (k[2] < p[2] ? -1 : k[2] > p[2] ? 1 : 0)
                    } while (0 == b)
                }
                b = hb[a] = 0 <= b
            }
            return b
        },
        jb = {},
        kb = function(a) {
            return jb[a] || (jb[a] = I && document.documentMode && document.documentMode >= a)
        };
    !I || kb(9);
    !Wa && !I || I && kb(9) || Wa && ib("1.9.1");
    I && ib("9");
    var mb = function(a) {
            return a ? new lb(K(a)) : Ca || (Ca = new lb)
        },
        L = function(a) {
            return w(a) ? document.getElementById(a) : a
        },
        M = function(a, b) {
            var c = b || document;
            return nb(c) ? c.querySelectorAll("." + a) : c.getElementsByClassName ? c.getElementsByClassName(a) : ob("*", a, b)
        },
        N = function(a, b) {
            var c = b || document,
                d = n;
            return (d = nb(c) ? c.querySelector("." + a) : M(a, b)[0]) || n
        },
        nb = function(a) {
            return a.querySelectorAll && a.querySelector && (!J || pb(document) || ib("528"))
        },
        ob = function(a, b, c) {
            c = c || document;
            a = a && "*" != a ? a.toUpperCase() : "";
            if (nb(c) &&
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
                for (f = e = 0; g = c[f]; f++) a = g.className, "function" == typeof a.split && 0 <= C(a.split(/\s+/), b) && (d[e++] = g);
                d.length = e;
                return d
            }
            return c
        },
        pb = function(a) {
            return "CSS1Compat" == a.compatMode
        },
        qb = function(a) {
            a && a.parentNode && a.parentNode.removeChild(a)
        },
        K = function(a) {
            return 9 ==
                a.nodeType ? a : a.ownerDocument || a.document
        },
        O = function(a, b, c) {
            var d = b ? b.toUpperCase() : n;
            return rb(a, function(a) {
                return (!d || a.nodeName == d) && (!c || 0 <= C(F(a), c))
            }, l)
        },
        P = function(a, b) {
            return O(a, n, b)
        },
        rb = function(a, b, c, d) {
            c || (a = a.parentNode);
            for (var c = d == n, e = 0; a && (c || e <= d);) {
                if (b(a)) return a;
                a = a.parentNode;
                e++
            }
            return n
        },
        lb = function(a) {
            this.b = a || t.document || document
        };
    lb.prototype.createElement = function(a) {
        return this.b.createElement(a)
    };
    lb.prototype.createTextNode = function(a) {
        return this.b.createTextNode(a)
    };
    var sb = function(a) {
            return pb(a.b)
        },
        tb = function(a) {
            var b = a.b,
                a = !J && pb(b) ? b.documentElement : b.body,
                b = b.parentWindow || b.defaultView;
            return new H(b.pageXOffset || a.scrollLeft, b.pageYOffset || a.scrollTop)
        };
    lb.prototype.appendChild = function(a, b) {
        a.appendChild(b)
    };
    var wb = function(a) {
            this.b = a
        },
        xb = /\s*;\s*/;
    wb.prototype.set = function(a, b, c, d, e, f) {
        if (/[;=\s]/.test(a)) throw Error('Invalid cookie name "' + a + '"');
        if (/[;\r\n]/.test(b)) throw Error('Invalid cookie value "' + b + '"');
        v(c) || (c = -1);
        e = e ? ";domain=" + e : "";
        d = d ? ";path=" + d : "";
        f = f ? ";secure" : "";
        c = 0 > c ? "" : 0 == c ? ";expires=" + (new Date(1970, 1, 1)).toUTCString() : ";expires=" + (new Date(ha() + 1E3 * c)).toUTCString();
        this.b.cookie = a + "=" + b + e + d + c + f
    };
    wb.prototype.get = function(a, b) {
        for (var c = a + "=", d = (this.b.cookie || "").split(xb), e = 0, f; f = d[e]; e++)
            if (0 == f.indexOf(c)) return f.substr(c.length);
        return b
    };
    wb.prototype.remove = function(a, b, c) {
        var d = v(this.get(a));
        this.set(a, "", 0, b, c);
        return d
    };
    wb.prototype.clear = function() {
        for (var a = (this.b.cookie || "").split(xb), b = [], c = [], d, e, f = 0; e = a[f]; f++) d = e.indexOf("="), -1 == d ? (b.push(""), c.push(e)) : (b.push(e.substring(0, d)), c.push(e.substring(d + 1)));
        for (a = b.length - 1; 0 <= a; a--) this.remove(b[a])
    };
    var yb = new wb(document);
    yb.Qc = 3950;
    var zb = function(a, b, c) {
            yb.set("" + a, b, c, "/", "youtube.com")
        },
        Ab = function() {
            yb.remove("feed_view", "/", "youtube.com")
        };
    var Bb = function() {
        var a;
        a = this.ta;
        if (a = yb.get("" + a, h)) {
            a = unescape(a).split("&");
            for (var b = 0; b < a.length; b++) {
                var c = a[b].split("="),
                    d = c[0];
                (c = c[1]) && (Q[d] = c.toString())
            }
        }
    };
    aa(Bb);
    var Q = u("yt.prefs.UserPrefs.prefs_") || {};
    y("yt.prefs.UserPrefs.prefs_", Q);
    Bb.prototype.ta = "PREF";
    var Cb = function(a) {
            if (/^f([1-9][0-9]*)$/.test(a)) throw "ExpectedRegexMatch: " + a;
        },
        Db = function(a) {
            if (!/^\w+$/.test(a)) throw "ExpectedRegexMismatch: " + a;
        };
    Bb.prototype.get = function(a, b) {
        Db(a);
        Cb(a);
        var c = Q[a] !== h ? Q[a].toString() : n;
        return c != n ? c : b ? b : ""
    };
    Bb.prototype.set = function(a, b) {
        Db(a);
        Cb(a);
        if (b == n) throw "ExpectedNotNull";
        Q[a] = b.toString()
    };
    var Eb = function(a, b) {
        var c = "f" + (Math.floor(a / 31) + 1),
            d = 1 << a % 31,
            e = Q[c] !== h ? Q[c].toString() : n,
            e = (e != n && /^[A-Fa-f0-9]+$/.test(e) ? parseInt(e, 16) : n) || 0,
            e = b ? e | d : e & ~d;
        0 == e ? delete Q[c] : (d = e.toString(16), Q[c] = d.toString())
    };
    Bb.prototype.remove = function(a) {
        Db(a);
        Cb(a);
        delete Q[a]
    };
    Bb.prototype.save = function(a) {
        var a = 86400 * (a || 7),
            b = this.ta,
            c = [],
            d;
        for (d in Q) c.push(d + "=" + escape(Q[d]));
        zb(b, c.join("&"), a)
    };
    Bb.prototype.clear = function() {
        Q = {}
    };
    var Fb = {
        kc: 0,
        ob: 1,
        jb: 2,
        Pb: 3,
        pb: 4,
        Jc: 5,
        Lc: 6,
        Ic: 7,
        Gc: 8,
        Hc: 9,
        Kc: 10,
        Fc: 11,
        rc: 12,
        qc: 13,
        pc: 14,
        Gb: 15,
        ac: 16,
        dc: 17,
        ec: 18,
        cc: 19,
        bc: 20,
        sc: 21,
        sb: 22,
        Ec: 23,
        rb: 24,
        Ya: 25,
        tb: 26,
        Eb: 27,
        nc: 28,
        qb: 29,
        mc: 30,
        zc: 31,
        yc: 32,
        Bb: 33,
        wc: 34,
        tc: 35,
        uc: 36,
        vc: 37,
        xc: 38,
        Qb: 39,
        hc: 40,
        Za: 41,
        gc: 42,
        ab: 43,
        ib: 44,
        vb: 45,
        Yb: 46,
        Ac: 47,
        Mc: 48,
        Nc: 49,
        Pc: 50,
        oc: 51,
        hb: 52,
        lb: 53,
        Zb: 54,
        Lb: 55,
        ub: 56,
        lc: 57,
        ic: 58,
        Db: 59,
        Vb: 60,
        Mb: 61,
        Rb: 62,
        $a: 63,
        Dc: 64,
        eb: 65,
        cb: 66,
        Sb: 67,
        nb: 68,
        xb: 69,
        Hb: 70,
        Wb: 71,
        Fb: 72,
        jc: 73,
        Tb: 74,
        Bc: 75,
        bb: 76,
        fc: 77,
        yb: 78,
        Cc: 79,
        Nb: 80,
        kb: 81,
        Ub: 82,
        Ib: 83,
        Kb: 84,
        Jb: 85,
        qa: 86,
        ra: 87,
        fb: 88,
        Xa: 89,
        gb: 90,
        $b: 91,
        Xb: 92,
        mb: 93,
        Oc: 94,
        Ab: 95,
        zb: 96,
        Cb: 97,
        Ob: 98,
        wb: 99
    };
    var R = function(a, b, c) {
            a.dataset ? a.dataset[Gb(b)] = c : a.setAttribute("data-" + b, c)
        },
        S = function(a, b) {
            return a.dataset ? a.dataset[Gb(b)] : a.getAttribute("data-" + b)
        },
        Hb = function(a, b) {
            a.dataset ? delete a.dataset[Gb(b)] : a.removeAttribute("data-" + b)
        },
        Ib = {},
        Gb = function(a) {
            return Ib[a] || (Ib[a] = ("" + a).replace(/\-([a-z])/g, function(a, c) {
                return c.toUpperCase()
            }))
        };
    var Kb = function(a) {
            var b = a.__yt_uid_key;
            b || (b = Jb(), a.__yt_uid_key = b);
            return b
        },
        Jb = u("yt.dom.getNextId_");
    if (!Jb) {
        Jb = function() {
            return ++Lb
        };
        y("yt.dom.getNextId_", Jb);
        var Lb = 0
    }
    var Mb = function(a) {
        var b = document.createElement("div");
        b.innerHTML = a;
        if (b.firstElementChild != h) a = b.firstElementChild;
        else
            for (a = b.firstChild; a && 1 != a.nodeType;) a = a.nextSibling;
        return a
    };
    var Nb = function(a) {
        if (a = a || u("window.event")) {
            this.type = a.type;
            var b = a.target || a.srcElement;
            b && 3 == b.nodeType && (b = b.parentNode);
            this.target = b;
            if (b = a.relatedTarget) try {
                b = b.nodeName && b
            } catch (c) {
                b = n
            } else "mouseover" == this.type ? b = a.fromElement : "mouseout" == this.type && (b = a.toElement);
            this.relatedTarget = b;
            this.data = a.data;
            this.source = a.source;
            this.origin = a.origin;
            this.state = a.state;
            this.clientX = a.clientX !== h ? a.clientX : a.pageX;
            this.clientY = a.clientY !== h ? a.clientY : a.pageY;
            if (a.pageX || a.pageY) this.pageX = a.pageX,
                this.pageY = a.pageY;
            else if ((a.clientX || a.clientY) && document.body && document.documentElement) this.pageX = a.clientX + document.body.scrollLeft + document.documentElement.scrollLeft, this.pageY = a.clientY + document.body.scrollTop + document.documentElement.scrollTop;
            this.keyCode = a.keyCode ? a.keyCode : a.which;
            this.charCode = a.charCode || ("keypress" == this.type ? this.keyCode : 0);
            0 == this.type.indexOf("touch") && (this.touches = a.touches, this.changedTouches = a.changedTouches);
            0 == this.type.indexOf("gesture") && (this.scale = a.scale,
                this.rotation = a.rotation);
            this.P = a
        }
    };
    r = Nb.prototype;
    r.type = "";
    r.target = n;
    r.relatedTarget = n;
    r.currentTarget = n;
    r.data = n;
    r.source = n;
    r.origin = n;
    r.state = n;
    r.keyCode = 0;
    r.charCode = 0;
    r.P = n;
    r.clientX = 0;
    r.clientY = 0;
    r.pageX = 0;
    r.pageY = 0;
    r.touches = n;
    r.changedTouches = n;
    r.preventDefault = function() {
        this.P.returnValue = o;
        this.P.preventDefault && this.P.preventDefault()
    };
    var Ja = u("yt.events.listeners_") || {};
    y("yt.events.listeners_", Ja);
    var Ob = u("yt.events.counter_") || {
        count: 0
    };
    y("yt.events.counter_", Ob);
    var Pb = function(a, b, c, d) {
            return Ka(function(e) {
                return e[0] == a && e[1] == b && e[2] == c && e[4] == !!d
            })
        },
        T = function(a, b, c, d) {
            if (!a || !a.addEventListener && !a.attachEvent) return "";
            var d = !!d,
                e = Pb(a, b, c, d);
            if (e) return e;
            var e = ++Ob.count + "",
                f = function(b) {
                    b = new Nb(b);
                    b.currentTarget = a;
                    return c.call(a, b)
                };
            Ja[e] = [a, b, c, f, d];
            a.addEventListener ? a.addEventListener(b, f, d) : a.attachEvent("on" + b, f);
            return e
        },
        Rb = function(a, b) {
            var c;
            c = T(a, J ? "webkitTransitionEnd" : Va ? "oTransitionEnd" : "transitionend", function() {
                Qb(c);
                b.apply(a,
                    arguments)
            }, h)
        },
        U = function(a, b, c) {
            Sb(a, b, function(a) {
                return 0 <= C(F(a), c)
            })
        },
        Sb = function(a, b, c) {
            var d = a || document;
            T(d, "click", function(a) {
                var f = rb(a.target, function(a) {
                    return a === d || c(a)
                }, l);
                f && f !== d && (a.currentTarget = f, b.call(f, a))
            })
        },
        Qb = function(a) {
            "string" == typeof a && (a = [a]);
            E(a, function(a) {
                if (a in Ja) {
                    var c = Ja[a],
                        d = c[0],
                        e = c[1],
                        f = c[3],
                        c = c[4];
                    d.removeEventListener ? d.removeEventListener(e, f, c) : d.detachEvent("on" + e, f);
                    delete Ja[a]
                }
            })
        };
    var Tb = window.yt && window.yt.config_ || {};
    y("yt.config_", Tb);
    y("yt.globals_", window.yt && window.yt.globals_ || {});
    y("yt.msgs_", window.yt && window.yt.msgs_ || {});
    var Ub = window.yt && window.yt.timeouts_ || [];
    y("yt.timeouts_", Ub);
    var Vb = window.yt && window.yt.intervals_ || [];
    y("yt.intervals_", Vb);
    var Wb = function(a) {
            return a in Tb ? Tb[a] : h
        },
        Xb = function(a, b) {
            var c = window.setTimeout(a, b);
            Ub.push(c)
        };
    eval("/*@cc_on!@*/false");
    var Yb = function(a) {
            this.za = 1E3 / a;
            this.M = n;
            this.q = []
        },
        Zb = new Yb(24);
    Yb.prototype.Ba = function() {
        for (var a = ha(), b = this.q.length - 1; 0 <= b; b--) $b(this.q[b], a) && ac(this, b)
    };
    Yb.prototype.add = function(a) {
        this.q.push(a);
        this.M || (a = x(this.Ba, this), a = window.setInterval(a, this.za), Vb.push(a), this.M = a)
    };
    Yb.prototype.remove = function(a) {
        a = C(this.q, a);
        0 <= a && ac(this, a)
    };
    var ac = function(a, b) {
        xa(a.q, b);
        a.q.length || (window.clearInterval(a.M), delete a.M)
    };
    var bc = function(a, b, c, d, e, f, g, i) {
        this.o = a;
        this.G = b;
        this.D = c;
        this.H = d;
        this.F = e;
        this.I = f;
        this.C = g;
        this.Y = i
    };
    bc.prototype.p = function() {
        return new bc(this.o, this.G, this.D, this.H, this.F, this.I, this.C, this.Y)
    };
    var cc = function(a, b) {
            if (0 == b) return new H(a.o, a.G);
            if (1 == b) return new H(a.C, a.Y);
            var c = a.o + b * (a.D - a.o),
                d = a.G + b * (a.H - a.G),
                e = a.D + b * (a.F - a.D),
                f = a.H + b * (a.I - a.H),
                g = a.F + b * (a.C - a.F),
                i = a.I + b * (a.Y - a.I),
                c = c + b * (e - c),
                d = d + b * (f - d);
            return new H(c + b * (e + b * (g - e) - c), d + b * (f + b * (i - f) - d))
        },
        dc = function(a, b) {
            var c = (b - a.o) / (a.C - a.o);
            if (0 >= c) return 0;
            if (1 <= c) return 1;
            for (var d = 0, e = 1, f = 0; 8 > f; f++) {
                var g = cc(a, c).x,
                    i = (cc(a, c + 1.0E-6).x - g) / 1.0E-6;
                if (1.0E-6 > Math.abs(g - b)) return c;
                if (1.0E-6 > Math.abs(i)) break;
                else g < b ? d = c : e = c, c -= (g -
                    b) / i
            }
            for (f = 0; 1.0E-6 < Math.abs(g - b) && 8 > f; f++) g < b ? (d = c, c = (c + e) / 2) : (e = c, c = (c + d) / 2), g = cc(a, c).x;
            return c
        };
    var ec = function(a, b) {
            this.j = new bc(0, 0, a.x, a.y, b.x, b.y, 1, 1)
        },
        fc = function(a) {
            return a
        },
        gc = new ec({
            x: 0.25,
            y: 0.1
        }, {
            x: 0.25,
            y: 1
        }),
        hc = function(a) {
            return cc(gc.j, dc(gc.j, a)).y
        },
        ic = new ec({
            x: 0.42,
            y: 0
        }, {
            x: 1,
            y: 1
        }),
        jc = function(a) {
            return cc(ic.j, dc(ic.j, a)).y
        },
        kc = new ec({
            x: 0,
            y: 0
        }, {
            x: 0.58,
            y: 1
        }),
        lc = function(a) {
            return cc(kc.j, dc(kc.j, a)).y
        },
        mc = new ec({
            x: 0.42,
            y: 0
        }, {
            x: 0.58,
            y: 1
        }),
        nc = function(a) {
            return cc(mc.j, dc(mc.j, a)).y
        },
        oc = function(a) {
            switch (a) {
                case "linear":
                    return fc;
                case "ease-in":
                    return jc;
                case "ease-out":
                    return lc;
                case "ease-in-out":
                    return nc;
                default:
                    return hc
            }
        };
    var pc = function(a, b) {
        var c = b || {};
        this.a = a;
        this.ga = c.duration || 0.25;
        this.S = 1E3 * this.ga;
        this.w = c.l || n;
        this.da = c.Ua || "ease";
        this.g(c);
        c.Ta || this.play()
    };
    pc.prototype.g = function() {};
    var qc, rc = function() {
        if (!v(qc)) {
            var a = document.createElement("div");
            qc = v(a.style.MozTransition) ? "Moz" : v(a.style.WebkitTransition) ? "Webkit" : v(a.style.Rc) ? "O" : n
        }
        return qc
    };
    var sc = function(a, b) {
        pc.call(this, a, b)
    };
    A(sc, pc);
    var tc = function(a, b, c) {
        b = rc() + b;
        a.a.style[b] = c
    };
    sc.prototype.play = function() {
        this.a.style.opacity = this.A;
        Xb(x(function() {
            tc(this, "TransitionTimingFunction", this.da);
            tc(this, "TransitionDuration", this.ga + "s");
            tc(this, "TransitionProperty", "opacity");
            Rb(this.a, x(function() {
                tc(this, "TransitionTimingFunction", "");
                tc(this, "TransitionDuration", "");
                tc(this, "TransitionProperty", "");
                this.w && this.w(this)
            }, this));
            this.a.style.opacity = this.z
        }, this), 20)
    };
    var uc = function(a, b) {
        pc.call(this, a, b)
    };
    A(uc, pc);
    uc.prototype.g = function(a) {
        this.B = 0;
        this.Aa = a.loop || Zb;
        this.va = oc(this.da)
    };
    uc.prototype.play = function() {
        this.T = ha();
        $b(this, this.T);
        this.Aa.add(this)
    };
    var $b = function(a, b) {
        a.B = Math.min(a.B + (b - a.T), a.S);
        a.T = b;
        var c = a.va(a.B / a.S),
            c = a.A - (a.A - a.z) * c;
        a.fa ? a.a.style.filter = "alpha(opacity=" + Math.floor(100 * c) + ")" : a.a.style.opacity = c;
        if (c = a.B >= a.S) a.ha(), a.w && Xb(x(a.w, t, a), 0);
        return c
    };
    uc.prototype.ha = function() {};
    var vc = function(a, b) {
        pc.call(this, a, b)
    };
    A(vc, uc);
    vc.prototype.g = function(a) {
        var b = parseFloat(a.start),
            c = parseFloat(a.end);
        this.A = isNaN(b) ? 1 : b;
        this.z = isNaN(c) ? 0 : c;
        this.fa = !v(this.a.style.opacity);
        vc.ia.g.call(this, a)
    };
    vc.prototype.ha = function() {
        this.fa && 1 == this.z && (this.a.style.filter = "")
    };
    var wc = function(a, b) {
        pc.call(this, a, b)
    };
    A(wc, sc);
    wc.prototype.g = function(a) {
        var b = parseFloat(a.start),
            c = parseFloat(a.end);
        this.A = isNaN(b) ? 1 : b;
        this.z = isNaN(c) ? 0 : c;
        wc.ia.g.call(this, a)
    };
    var xc = function(a, b) {
            var c = b || {};
            c.start = 0;
            c.end = 1;
            rc() ? new wc(a, c) : new vc(a, c)
        },
        yc = function(a, b) {
            var c = b || {};
            c.start = 1;
            c.end = 0;
            rc() ? new wc(a, c) : new vc(a, c)
        };
    var zc = function(a, b, c) {
            a.push(encodeURIComponent(b) + "=" + encodeURIComponent(c))
        },
        Ac = function(a) {
            var b = a.type;
            if (!v(b)) return n;
            switch (b.toLowerCase()) {
                case "checkbox":
                case "radio":
                    return a.checked ? a.value : n;
                case "select-one":
                    return b = a.selectedIndex, 0 <= b ? a.options[b].value : n;
                case "select-multiple":
                    for (var b = [], c, d = 0; c = a.options[d]; d++) c.selected && b.push(c.value);
                    return b.length ? b : n;
                default:
                    return v(a.value) ? a.value : n
            }
        };
    var Bc = function(a) {
        return eval("(" + a + ")")
    };
    var Cc = function(a) {
            if (a[1]) {
                var b = a[0],
                    c = b.indexOf("#");
                0 <= c && (a.push(b.substr(c)), a[0] = b = b.substr(0, c));
                c = b.indexOf("?");
                0 > c ? a[1] = "?" : c == b.length - 1 && (a[1] = h)
            }
            return a.join("")
        },
        Dc = function(a, b) {
            for (var c in b) {
                var d = c,
                    e = b[c],
                    f = a;
                if (ca(e))
                    for (var g = 0; g < e.length; g++) f.push("&", d), "" !== e[g] && f.push("=", la(e[g]));
                else e != n && (f.push("&", d), "" !== e && f.push("=", la(e)))
            }
            return a
        };
    var Ec = function(a) {
            a = Dc([], a);
            a[0] = "";
            return a.join("")
        },
        Fc = function(a, b) {
            var c = a.split("?", 2),
                a = c[0],
                c = c[1] || "";
            "?" == c.charAt(0) && (c = c.substr(1));
            for (var c = c.split("&"), d = {}, e = 0, f = c.length; e < f; e++) {
                var g = c[e].split("=");
                if (1 == g.length && g[0] || 2 == g.length) {
                    var i = g[0],
                        g = decodeURIComponent((g[1] || "").replace(/\+/g, " "));
                    i in d ? ca(d[i]) ? ya(d[i], g) : d[i] = [d[i], g] : d[i] = g
                }
            }
            for (var j in b) d[j] = b[j];
            return Cc(Dc([a], d))
        };
    var Gc = n;
    "undefined" != typeof XMLHttpRequest ? Gc = function() {
        return new XMLHttpRequest
    } : "undefined" != typeof ActiveXObject && (Gc = function() {
        return new ActiveXObject("Microsoft.XMLHTTP")
    });
    var Hc = function(a) {
        switch (a && "status" in a ? a.status : -1) {
            case 0:
            case 200:
            case 204:
            case 304:
                return l;
            default:
                return o
        }
    };
    var Ic = function(a, b, c, d, e) {
            var f = Gc && Gc();
            if ("open" in f) {
                f.onreadystatechange = function() {
                    4 == (f && "readyState" in f ? f.readyState : 0) && b && b(f)
                };
                c = (c || "GET").toUpperCase();
                d = d || "";
                f.open(c, a, l);
                a = "POST" == c;
                if (e)
                    for (var g in e) f.setRequestHeader(g, e[g]), "content-type" == g.toLowerCase() && (a = o);
                a && f.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                f.send(d)
            }
        },
        Kc = function(a, b) {
            var c = b.format || "JSON";
            b.Va && (a = "//" + document.location.hostname + a);
            var d = b.d;
            d && (a = Fc(a, d));
            var d = b.Wa || "",
                e = b.m;
            if (d && e) throw Error();
            e && (d = Ec(e));
            Ic(a, function(a) {
                var d = Hc(a),
                    e = n;
                if (d || 400 <= a.status && 500 > a.status) e = Jc(c, a);
                if (d) a: {
                    switch (c) {
                        case "XML":
                            d = 0 == parseInt(e && e.return_code, 10);
                            break a;
                        case "RAW":
                            d = l;
                            break a
                    }
                    d = !!e
                }
                var e = e || {},
                    j = b.Q || t;
                d ? b.f && b.f.call(j, a, e) : b.h && b.h.call(j, a, e);
                b.l && b.l.call(j, a, e)
            }, b.method, d, b.headers)
        },
        Jc = function(a, b) {
            var c = n;
            switch (a) {
                case "JSON":
                    var d = b.responseText,
                        e = b.getResponseHeader("Content-Type") || "";
                    d && 0 <= e.indexOf("json") && (c = Bc(d));
                    break;
                case "XML":
                    if (d = (d = b.responseXML) ?
                        Lc(d) : n) c = {}, E(d.getElementsByTagName("*"), function(a) {
                        c[a.tagName] = Mc(a)
                    })
            }
            return c
        },
        Oc = function(a, b) {
            var c = b.onComplete || n,
                d = b.onException || n,
                e = b.onError || n,
                f = b.update || n,
                g = b.json || o;
            Ic(a, function(a) {
                if (Hc(a)) {
                    var b = a.responseXML,
                        m = b ? Lc(b) : n,
                        b = !(!b || !m),
                        k, p;
                    if (b && (k = Nc(m, "return_code"), p = Nc(m, "html_content"), 0 == k)) {
                        f && p && (L(f).innerHTML = p);
                        var s = Nc(m, "js_content");
                        if (s) {
                            var z = document.createElement("script");
                            z.text = s;
                            document.getElementsByTagName("head")[0].appendChild(z)
                        }
                    }
                    c && (b ? (b = Nc(m, "redirect_on_success"),
                        k && b ? window.location = b : ((m = Nc(m, 0 == k ? "success_message" : "error_message")) && alert(m), a = g ? eval("(" + p + ")") : a, 0 == k ? c(a) : d && d(a))) : a.responseText && c(a))
                } else e && e(a)
            }, b.method || "POST", b.postBody || n, b.headers || n)
        },
        Lc = function(a) {
            return !a ? n : (a = ("responseXML" in a ? a.responseXML : a).getElementsByTagName("root")) && 0 < a.length ? a[0] : n
        },
        Nc = function(a, b) {
            if (!a) return n;
            var c = a.getElementsByTagName(b);
            return c && 0 < c.length ? Mc(c[0]) : n
        },
        Mc = function(a) {
            var b = "";
            E(a.childNodes, function(a) {
                b += a.nodeValue
            });
            return b
        },
        Pc = u("yt.net.ajax.tokenMap_") || {};
    y("yt.net.ajax.tokenMap_", Pc);
    var Qc = function(a, b, c, d) {
        this.top = a;
        this.right = b;
        this.bottom = c;
        this.left = d
    };
    Qc.prototype.p = function() {
        return new Qc(this.top, this.right, this.bottom, this.left)
    };
    Qc.prototype.toString = function() {
        return "(" + this.top + "t, " + this.right + "r, " + this.bottom + "b, " + this.left + "l)"
    };
    var Rc = function(a, b, c, d) {
        this.left = a;
        this.top = b;
        this.width = c;
        this.height = d
    };
    Rc.prototype.p = function() {
        return new Rc(this.left, this.top, this.width, this.height)
    };
    Rc.prototype.toString = function() {
        return "(" + this.left + ", " + this.top + " - " + this.width + "w x " + this.height + "h)"
    };
    var V = function(a, b) {
            var c = K(a);
            return c.defaultView && c.defaultView.getComputedStyle && (c = c.defaultView.getComputedStyle(a, n)) ? c[b] || c.getPropertyValue(b) : ""
        },
        Sc = function(a, b) {
            return a.currentStyle ? a.currentStyle[b] : n
        },
        Tc = function(a, b) {
            return V(a, b) || Sc(a, b) || a.style && a.style[b]
        },
        Uc = function(a) {
            var b = a.getBoundingClientRect();
            I && (a = a.ownerDocument, b.left -= a.documentElement.clientLeft + a.body.clientLeft, b.top -= a.documentElement.clientTop + a.body.clientTop);
            return b
        },
        Vc = function(a) {
            if (I && !kb(8)) return a.offsetParent;
            for (var b = K(a), c = Tc(a, "position"), d = "fixed" == c || "absolute" == c, a = a.parentNode; a && a != b; a = a.parentNode)
                if (c = Tc(a, "position"), d = d && "static" == c && a != b.documentElement && a != b.body, !d && (a.scrollWidth > a.clientWidth || a.scrollHeight > a.clientHeight || "fixed" == c || "absolute" == c || "relative" == c)) return a;
            return n
        },
        Yc = function(a) {
            for (var b = new Qc(0, Infinity, Infinity, 0), c = mb(a), d = c.b.body, e = c.b.documentElement, f = !J && pb(c.b) ? c.b.documentElement : c.b.body; a = Vc(a);)
                if ((!I || 0 != a.clientWidth) && (!J || 0 != a.clientHeight || a != d) &&
                    a != d && a != e && "visible" != Tc(a, "overflow")) {
                    var g = Wc(a),
                        i;
                    i = a;
                    if (Wa && !ib("1.9")) {
                        var j = parseFloat(V(i, "borderLeftWidth"));
                        if (Xc(i)) var m = i.offsetWidth - i.clientWidth - j - parseFloat(V(i, "borderRightWidth")),
                            j = j + m;
                        i = new H(j, parseFloat(V(i, "borderTopWidth")))
                    } else i = new H(i.clientLeft, i.clientTop);
                    g.x += i.x;
                    g.y += i.y;
                    b.top = Math.max(b.top, g.y);
                    b.right = Math.min(b.right, g.x + a.clientWidth);
                    b.bottom = Math.min(b.bottom, g.y + a.clientHeight);
                    b.left = Math.max(b.left, g.x)
                } d = f.scrollLeft;
            f = f.scrollTop;
            b.left = Math.max(b.left,
                d);
            b.top = Math.max(b.top, f);
            c = c.b.parentWindow || c.b.defaultView || window;
            e = c.document;
            J && !ib("500") && !Xa ? ("undefined" == typeof c.innerHeight && (c = window), e = c.innerHeight, a = c.document.documentElement.scrollHeight, c == c.top && a < e && (e -= 15), c = new Ia(c.innerWidth, e)) : (c = pb(e) ? e.documentElement : e.body, c = new Ia(c.clientWidth, c.clientHeight));
            b.right = Math.min(b.right, d + c.width);
            b.bottom = Math.min(b.bottom, f + c.height);
            return 0 <= b.top && 0 <= b.left && b.bottom > b.top && b.right > b.left ? b : n
        },
        Wc = function(a) {
            var b, c = K(a),
                d = Tc(a,
                    "position"),
                e = Wa && c.getBoxObjectFor && !a.getBoundingClientRect && "absolute" == d && (b = c.getBoxObjectFor(a)) && (0 > b.screenX || 0 > b.screenY),
                f = new H(0, 0),
                g;
            b = c ? 9 == c.nodeType ? c : K(c) : document;
            g = I && !kb(9) && !sb(mb(b)) ? b.body : b.documentElement;
            if (a == g) return f;
            if (a.getBoundingClientRect) b = Uc(a), a = tb(mb(c)), f.x = b.left + a.x, f.y = b.top + a.y;
            else if (c.getBoxObjectFor && !e) b = c.getBoxObjectFor(a), a = c.getBoxObjectFor(g), f.x = b.screenX - a.screenX, f.y = b.screenY - a.screenY;
            else {
                b = a;
                do {
                    f.x += b.offsetLeft;
                    f.y += b.offsetTop;
                    b != a && (f.x +=
                        b.clientLeft || 0, f.y += b.clientTop || 0);
                    if (J && "fixed" == Tc(b, "position")) {
                        f.x += c.body.scrollLeft;
                        f.y += c.body.scrollTop;
                        break
                    }
                    b = b.offsetParent
                } while (b && b != a);
                if (Va || J && "absolute" == d) f.y -= c.body.offsetTop;
                for (b = a;
                    (b = Vc(b)) && b != c.body && b != g;)
                    if (f.x -= b.scrollLeft, !Va || "TR" != b.tagName) f.y -= b.scrollTop
            }
            return f
        },
        Zc = function(a, b) {
            "number" == typeof a && (a = (b ? Math.round(a) : a) + "px");
            return a
        },
        ad = function(a) {
            if ("none" != Tc(a, "display")) return $c(a);
            var b = a.style,
                c = b.display,
                d = b.visibility,
                e = b.position;
            b.visibility =
                "hidden";
            b.position = "absolute";
            b.display = "inline";
            a = $c(a);
            b.display = c;
            b.position = e;
            b.visibility = d;
            return a
        },
        $c = function(a) {
            var b = a.offsetWidth,
                c = a.offsetHeight,
                d = J && !b && !c;
            return (!v(b) || d) && a.getBoundingClientRect ? (a = Uc(a), new Ia(a.right - a.left, a.bottom - a.top)) : new Ia(b, c)
        },
        Xc = function(a) {
            return "rtl" == Tc(a, "direction")
        },
        bd = function(a, b) {
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
        cd = {
            thin: 2,
            medium: 4,
            thick: 6
        },
        dd = function(a, b) {
            if ("none" == Sc(a, b + "Style")) return 0;
            var c = Sc(a, b + "Width");
            return c in cd ? cd[c] : bd(a, c)
        };
    var ed = function(a, b) {
            if ((a = L(a)) && a.style) a.style.display = b ? "" : "none", G(a, "hid", !b)
        },
        fd = function(a) {
            E(arguments, function(a) {
                ed(a, l)
            })
        },
        gd = function(a) {
            E(arguments, function(a) {
                ed(a, o)
            })
        };
    var W = function(a, b, c, d, e) {
        this.U = "session_token=" + a;
        if ((this.c = b) && "/" != this.c.charAt(this.c.length - 1)) this.c += "/";
        this.t = d;
        this.V = e;
        this.J = n;
        this.$ = [];
        this.Z = [];
        this.X = [];
        this.W = {}
    };
    y("yt.sharing.AutoShare", W);
    W.prototype.N = function(a, b, c, d) {
        T(a, "click", x(this.pa, this));
        if (a.id) this.W[a.id] = {
            serviceName: b,
            connectOnly: c
        }, d && (this.W[a.id].connectOnlyCallback = d);
        else throw "Connect dialog launch buttons must have an id.";
    };
    W.prototype.registerConnectDialogLauncher = W.prototype.N;
    W.prototype.pa = function(a) {
        if (a = this.W[a.currentTarget.id]) {
            var b = a.connectOnly;
            a.connectOnlyCallback && (b = (0, a.connectOnlyCallback)());
            this.ba(a.serviceName, b)
        }
    };
    W.prototype.handleConnectService = W.prototype.pa;
    W.prototype.Ma = function() {
        this.k()
    };
    W.prototype.doOnLoad = W.prototype.Ma;
    W.prototype.oa = function(a) {
        this.$.push(a)
    };
    W.prototype.addServiceChangedCallback = W.prototype.oa;
    W.prototype.Ia = function(a) {
        this.Z.push(a)
    };
    W.prototype.addGaiaChangedCallback = W.prototype.Ia;
    W.prototype.Ha = function(a) {
        this.X.push(a)
    };
    W.prototype.addCanConnectCallback = W.prototype.Ha;
    W.prototype.Oa = function() {
        return this.V
    };
    W.prototype.isGaiaUser = W.prototype.Oa;
    W.prototype.Ra = function() {
        this.la(this.c + "autoshare?action_link_start=1&root_url=" + encodeURIComponent(this.c), {
            height: 660,
            width: 1E3
        })
    };
    W.prototype.upgradeToGoogleAccount = W.prototype.Ra;
    W.prototype.Sa = function(a, b) {
        this.V = a;
        this.Ca();
        b && b()
    };
    W.prototype.upgradeToGoogleAccountDone = W.prototype.Sa;
    W.prototype.ua = function() {
        return this.t
    };
    W.prototype.getServiceInfo = W.prototype.ua;
    W.prototype.ba = function(a, b) {
        this.V || Oc(this.c + "autoshare?action_ajax_stats_ping=1&stat=connect_no_google&service=" + a, {
            method: "GET",
            onComplete: function() {}
        });
        for (var c in this.X)
            if (!(0, this.X[c])(this, a, b)) return;
        Oc(this.c + "autoshare?action_ajax_stats_ping=1&stat=connect_has_google&service=" + a, {
            method: "GET",
            onComplete: function() {}
        });
        c = this.c + "autoshare?action_popup_auth=1&service=" + a + "&connect_only=" + (b ? "True" : "False") + "&root_url=" + encodeURIComponent(this.c);
        if ("facebook" == a) {
            var d = "read_stream,offline_access";
            b || (d = [d, "publish_stream"].join());
            c += "&permissions=" + encodeURIComponent(d)
        }
        this.la(c, {
            height: 500,
            width: 860
        })
    };
    W.prototype.connectService = W.prototype.ba;
    W.prototype.Ka = function(a, b) {
        var c = x(function(a) {
                this.t = a;
                this.k();
                b && b()
            }, this),
            d = x(function() {
                b && b();
                this.k()
            }, this),
            e = {
                action_ajax_connect_service: 1
            };
        e.return_url = a;
        Oc(this.c + "autoshare?ajax_connect_service", {
            postBody: Ec(e) + "&" + this.U,
            onComplete: c,
            onException: d,
            json: l
        })
    };
    W.prototype.connectServiceDone = W.prototype.Ka;
    W.prototype.La = function(a) {
        this.Ga(a)
    };
    W.prototype.disconnectService = W.prototype.La;
    W.prototype.Pa = function(a, b) {
        var c = x(function(a) {
                this.t = a;
                this.k()
            }, this),
            d = x(function() {
                this.k()
            }, this),
            e = {
                action_ajax_set_connect_only: 1
            };
        e.service = a;
        e.connect_only = b ? "True" : "False";
        Oc(this.c + "autoshare?ajax_set_connect_only", {
            postBody: Ec(e) + "&" + this.U,
            onComplete: c,
            onException: d,
            json: l
        })
    };
    W.prototype.setConnectOnly = W.prototype.Pa;
    r = W.prototype;
    r.Ga = function(a) {
        var b = x(function(a) {
                this.t = a;
                this.k()
            }, this),
            c = x(function() {
                this.k()
            }, this),
            d = {
                action_ajax_disconnect_service: 1
            };
        d.service = a;
        Oc(this.c + "autoshare?ajax_disconnect_service", {
            postBody: Ec(d) + "&" + this.U,
            onComplete: b,
            onException: c,
            json: l
        })
    };
    r.Ca = function() {
        for (var a in this.Z)(0, this.Z[a])(this)
    };
    r.k = function() {
        for (var a in this.$)(0, this.$[a])(this)
    };
    r.la = function(a, b) {
        if (this.J) try {
            this.J.close()
        } catch (c) {
            this.J = n
        }
        var d;
        d = b || {};
        d.target = d.target || a.target || "YouTube";
        d.width = d.width || 600;
        d.height = d.height || 600;
        var e = d;
        e || (e = {});
        var f = window;
        d = "undefined" != typeof a.href ? a.href : "" + a;
        var g = e.target || a.target,
            i = [],
            j;
        for (j in e) switch (j) {
            case "width":
            case "height":
            case "top":
            case "left":
                i.push(j + "=" + e[j]);
                break;
            case "target":
            case "noreferrer":
                break;
            default:
                i.push(j + "=" + (e[j] ? 1 : 0))
        }
        j = i.join(",");
        if (e.noreferrer) {
            if (e = f.open("", g, j)) I && -1 != d.indexOf(";") &&
                (d = "'" + d.replace(/'/g, "%27") + "'"), e.opener = n, J ? e.location.href = d : (d = ra(d), e.document.write('<META HTTP-EQUIV="refresh" content="0; url=' + d + '">'), e.document.close())
        } else e = f.open(d, g, j);
        (d = e) ? (d.opener || (d.opener = window), d.focus(), d = o) : d = l;
        this.J = d
    };
    r.Sc = function() {
        this.ba("facebook", !this.t.facebook.is_autosharing)
    };
    var hd = function() {};
    var X = function() {
        this.e = [];
        this.i = {}
    };
    A(X, hd);
    r = X.prototype;
    r.ma = 1;
    r.L = 0;
    r.Qa = function(a, b, c) {
        var d = this.i[a];
        d || (d = this.i[a] = []);
        var e = this.ma;
        this.e[e] = a;
        this.e[e + 1] = b;
        this.e[e + 2] = c;
        this.ma = e + 3;
        d.push(e);
        return e
    };
    r.aa = function(a) {
        if (0 != this.L) return this.K || (this.K = []), this.K.push(a), o;
        var b = this.e[a];
        if (b) {
            var c = this.i[b];
            if (c) {
                var d = C(c, a);
                0 <= d && xa(c, d)
            }
            delete this.e[a];
            delete this.e[a + 1];
            delete this.e[a + 2]
        }
        return !!b
    };
    r.na = function(a, b) {
        var c = this.i[a];
        if (c) {
            this.L++;
            for (var d = Aa(arguments, 1), e = 0, f = c.length; e < f; e++) {
                var g = c[e];
                this.e[g + 1].apply(this.e[g + 2], d)
            }
            this.L--;
            if (this.K && 0 == this.L)
                for (; c = this.K.pop();) this.aa(c);
            return 0 != e
        }
        return o
    };
    r.clear = function(a) {
        if (a) {
            var b = this.i[a];
            b && (E(b, this.aa, this), delete this.i[a])
        } else this.e.length = 0, this.i = {}
    };
    var id = u("yt.pubsub.instance_") || new X;
    X.prototype.subscribe = X.prototype.Qa;
    X.prototype.unsubscribeByKey = X.prototype.aa;
    X.prototype.publish = X.prototype.na;
    X.prototype.clear = X.prototype.clear;
    y("yt.pubsub.instance_", id);
    var jd = function(a, b, c) {
            var d = u("yt.pubsub.instance_");
            d && d.subscribe(a, function() {
                var a = arguments;
                Xb(function() {
                    b.apply(c || t, a)
                }, 0)
            }, c)
        },
        kd = function(a, b) {
            var c = u("yt.pubsub.instance_");
            c && c.publish.apply(c, arguments)
        };
    var ld = {},
        md = 0,
        nd = function(a, b) {
            var c = new Image,
                d = "" + md++;
            ld[d] = c;
            c.onload = c.onerror = function() {
                b && ld[d] && b();
                delete ld[d]
            };
            c.src = a;
            c = eval("null")
        };
    var od = function(a) {
        var b = Wb("CONVERSION_URLS_DICT");
        b && a in b && nd(b[a])
    };
    var pd = function(a, b, c, d, e, f, g) {
        var i, j = c.offsetParent;
        if (j) {
            var m = "HTML" == j.tagName || "BODY" == j.tagName;
            if (!m || "static" != Tc(j, "position")) i = Wc(j), m || (i = Ha(i, new H(j.scrollLeft, j.scrollTop)))
        }
        j = Wc(a);
        m = ad(a);
        j = new Rc(j.x, j.y, m.width, m.height);
        if (m = Yc(a)) {
            var k = new Rc(m.left, m.top, m.right - m.left, m.bottom - m.top),
                m = Math.max(j.left, k.left),
                p = Math.min(j.left + j.width, k.left + k.width);
            if (m <= p) {
                var s = Math.max(j.top, k.top),
                    k = Math.min(j.top + j.height, k.top + k.height);
                s <= k && (j.left = m, j.top = s, j.width = p - m, j.height =
                    k - s)
            }
        }
        m = mb(a);
        s = mb(c);
        if (m.b != s.b) {
            var p = m.b.body,
                s = s.b.parentWindow || s.b.defaultView,
                k = new H(0, 0),
                z = K(p) ? K(p).parentWindow || K(p).defaultView : window,
                za = p;
            do {
                var $;
                if (z == s) $ = Wc(za);
                else {
                    var D = za;
                    $ = new H;
                    if (1 == D.nodeType)
                        if (D.getBoundingClientRect) D = Uc(D), $.x = D.left, $.y = D.top;
                        else {
                            var ub = tb(mb(D)),
                                D = Wc(D);
                            $.x = D.x - ub.x;
                            $.y = D.y - ub.y
                        }
                    else {
                        var ub = "function" == ba(D.ka),
                            vb = D;
                        D.targetTouches ? vb = D.targetTouches[0] : ub && D.ka().targetTouches && (vb = D.ka().targetTouches[0]);
                        $.x = vb.clientX;
                        $.y = vb.clientY
                    }
                }
                k.x += $.x;
                k.y += $.y
            } while (z && z != s && (za = z.frameElement) && (z = z.parent));
            p = Ha(k, Wc(p));
            I && !sb(m) && (p = Ha(p, tb(m)));
            j.left += p.x;
            j.top += p.y
        }
        a = (b & 4 && Xc(a) ? b ^ 2 : b) & -5;
        b = new H(a & 2 ? j.left + j.width : j.left, a & 1 ? j.top + j.height : j.top);
        i && (b = Ha(b, i));
        e && (b.x += (a & 2 ? -1 : 1) * e.x, b.y += (a & 1 ? -1 : 1) * e.y);
        var q;
        if (g && (q = Yc(c)) && i) q.top -= i.y, q.right -= i.x, q.bottom -= i.y, q.left -= i.x;
        a: {
            a = q;q = b.p();e = 0;b = (d & 4 && Xc(c) ? d ^ 2 : d) & -5;i = ad(c);d = i.p();
            if (f || 0 != b) b & 2 ? q.x -= d.width + (f ? f.right : 0) : f && (q.x += f.left),
            b & 1 ? q.y -= d.height + (f ? f.bottom : 0) : f && (q.y +=
                f.top);
            if (g) {
                if (a) {
                    f = q;
                    e = 0;
                    if (65 == (g & 65) && (f.x < a.left || f.x >= a.right)) g &= -2;
                    if (132 == (g & 132) && (f.y < a.top || f.y >= a.bottom)) g &= -5;
                    f.x < a.left && g & 1 && (f.x = a.left, e |= 1);
                    f.x < a.left && f.x + d.width > a.right && g & 16 && (d.width = Math.max(d.width - (f.x + d.width - a.right), 0), e |= 4);
                    f.x + d.width > a.right && g & 1 && (f.x = Math.max(a.right - d.width, a.left), e |= 1);
                    g & 2 && (e |= (f.x < a.left ? 16 : 0) | (f.x + d.width > a.right ? 32 : 0));
                    f.y < a.top && g & 4 && (f.y = a.top, e |= 2);
                    f.y >= a.top && f.y + d.height > a.bottom && g & 32 && (d.height = Math.max(d.height - (f.y + d.height - a.bottom),
                        0), e |= 8);
                    f.y + d.height > a.bottom && g & 4 && (f.y = Math.max(a.bottom - d.height, a.top), e |= 2);
                    g & 8 && (e |= (f.y < a.top ? 64 : 0) | (f.y + d.height > a.bottom ? 128 : 0));
                    g = e
                } else g = 256;
                e = g;
                if (e & 496) {
                    c = e;
                    break a
                }
            }
            f = Wa && (Qa || Za) && ib("1.9");q instanceof H ? (g = q.x, q = q.y) : (g = q, q = h);c.style.left = Zc(g, f);c.style.top = Zc(q, f);
            if (!(i == d || (!i || !d ? 0 : i.width == d.width && i.height == d.height))) f = sb(mb(K(c))),
            I && (!f || !ib("8")) ? (g = c.style, f ? (I ? (f = bd(c, Sc(c, "paddingLeft")), i = bd(c, Sc(c, "paddingRight")), q = bd(c, Sc(c, "paddingTop")), a = bd(c, Sc(c, "paddingBottom")),
                    f = new Qc(q, i, a, f)) : (f = V(c, "paddingLeft"), i = V(c, "paddingRight"), q = V(c, "paddingTop"), a = V(c, "paddingBottom"), f = new Qc(parseFloat(q), parseFloat(i), parseFloat(a), parseFloat(f))), I ? (i = dd(c, "borderLeft"), q = dd(c, "borderRight"), a = dd(c, "borderTop"), c = dd(c, "borderBottom"), c = new Qc(a, q, c, i)) : (i = V(c, "borderLeftWidth"), q = V(c, "borderRightWidth"), a = V(c, "borderTopWidth"), c = V(c, "borderBottomWidth"), c = new Qc(parseFloat(a), parseFloat(q), parseFloat(c), parseFloat(i))), g.pixelWidth = d.width - c.left - f.left - f.right - c.right,
                g.pixelHeight = d.height - c.top - f.top - f.bottom - c.bottom) : (g.pixelWidth = d.width, g.pixelHeight = d.height)) : (c = c.style, Wa ? c.MozBoxSizing = "border-box" : J ? c.WebkitBoxSizing = "border-box" : c.boxSizing = "border-box", c.width = Math.max(d.width, 0) + "px", c.height = Math.max(d.height, 0) + "px");c = e
        }
        return c
    };
    var qd = {},
        rd = "ontouchstart" in document,
        sd = function(a, b) {
            var c = qd[a].maxNumParents[b],
                d;
            0 < c ? d = c : -1 != a.indexOf("mouse") && (d = 2);
            return d
        },
        td = function(a, b, c) {
            return rb(b, function(b) {
                return 0 <= C(F(b), a)
            }, l, c) || n
        },
        Y = function(a) {
            if ("HTML" != a.target.tagName && a.type in qd) {
                var b = qd[a.type],
                    c;
                for (c in b.i) {
                    var d = sd(a.type, c),
                        e = td(c, a.target, d);
                    if (e) {
                        var f = l;
                        b.checkRelatedTarget[c] && a.relatedTarget && rb(a.relatedTarget, function(a) {
                            return a == e
                        }, l, d) && (f = o);
                        f && b.na(c, e, a.type, a)
                    }
                }
            }
        };
    T(document, "click", Y);
    T(document, "mouseover", Y);
    T(document, "mouseout", Y);
    T(document, "keydown", Y);
    T(document, "keyup", Y);
    T(document, "keypress", Y);
    T(document, "cut", Y);
    T(document, "paste", Y);
    rd && (T(document, "touchstart", Y), T(document, "touchend", Y), T(document, "touchcancel", Y));
    y("yt.uix.widgets_", window.yt && window.yt.uix && window.yt.uix.widgets_ || {});
    var ud = function() {};
    ud.prototype.Ja = function(a, b, c) {
        var d = this.getData(a, b);
        if (d && (d = u(d))) {
            var e = Aa(arguments, 2);
            Ba(e, 0, 0, a);
            d.apply(n, e)
        }
    };
    ud.prototype.getData = function(a, b) {
        return S(a, b)
    };
    ud.prototype.setData = function(a, b, c) {
        R(a, b, c)
    };
    var Z = function(a, b) {
        return "yt-uix" + (a.O ? "-" + a.O : "") + (b ? "-" + b : "")
    };
    var vd = function() {};
    A(vd, ud);
    vd.prototype.show = function(a) {
        var b = O(a, n, Z(this));
        if (b) {
            Ea(b, Z(this, "active"));
            var c = wd(this, a, b);
            if (c) {
                c.cardTargetNode = a;
                c.cardRootNode = b;
                xd(this, a, c);
                var d = Z(this, "card-visible");
                Xb(function() {
                    fd(c);
                    Ea(c, d)
                }, 10);
                this.Ja(b, "card-action", a)
            }
        }
    };
    var wd = function(a, b, c) {
            var d = Z(a, "card"),
                e = d + Kb(c),
                f = L(e);
            if (f) return f;
            c = yd(a, c);
            if (!c) return n;
            f = document.createElement("div");
            f.id = e;
            f.className = d;
            d = document.createElement("div");
            d.className = Z(a, "card-border");
            b = a.getData(b, "orientation") || "horizontal";
            e = document.createElement("div");
            e.className = "yt-uix-card-border-arrow yt-uix-card-border-arrow-" + b;
            var g = document.createElement("div");
            g.className = Z(a, "card-body");
            a = document.createElement("div");
            a.className = "yt-uix-card-body-arrow yt-uix-card-body-arrow-" +
                b;
            qb(c);
            g.appendChild(c);
            d.appendChild(a);
            d.appendChild(g);
            f.appendChild(e);
            f.appendChild(d);
            document.body.appendChild(f);
            return f
        },
        xd = function(a, b, c) {
            var d = a.getData(b, "orientation") || "horizontal",
                e = a.getData(b, "position"),
                f = !!a.getData(b, "force-position"),
                d = "horizontal" == d,
                g = "bottomright" == e || "bottomleft" == e,
                e = "topright" == e || "bottomright" == e,
                i, j;
            e && g ? (j = 7, i = 4) : e && !g ? (j = 6, i = 5) : !e && g ? (j = 5, i = 6) : (j = 4, i = 7);
            var m = Xc(document.body),
                k = Xc(b);
            m != k && (j ^= 2);
            var p;
            d ? (k = b.offsetHeight / 2 - 24, p = new H(-12, b.offsetHeight +
                6)) : (k = b.offsetWidth / 2 - 12, p = new H(b.offsetWidth + 6, -12));
            var s = n;
            f || (s = 10);
            var z = Z(a, "card-flip"),
                a = Z(a, "card-reverse");
            G(c, z, e);
            G(c, a, g);
            s = pd(b, j, c, i, p, n, s);
            !f && s && (s & 48 && (e = !e, j ^= 2, i ^= 2), s & 192 && (g = !g, j ^= 1, i ^= 1), G(c, z, e), G(c, a, g), pd(b, j, c, i, p));
            b = N("yt-uix-card-body-arrow", c);
            f = N("yt-uix-card-border-arrow", c);
            c = ad(c);
            k = Math.max(6, Math.min(k, (d ? c.height : c.width) - 24 - 6));
            c = d ? g ? "top" : "bottom" : !m && e || m && !e ? "left" : "right";
            b.setAttribute("style", "");
            f.setAttribute("style", "");
            b.style[c] = k + "px";
            f.style[c] =
                k + "px"
        },
        zd = function(a, b) {
            var c = O(b, n, Z(a));
            return !c ? o : 0 <= C(F(c), Z(a, "active"))
        },
        yd = function(a, b) {
            var c = b.cardContentNode;
            if (!c) {
                var d = Z(a, "content"),
                    e = Z(a, "card-content"),
                    f = c = N(d, b),
                    g = F(f);
                w(d) ? (d = C(g, d), 0 <= d && xa(g, d)) : ca(d) && Fa(g, d);
                w(e) && !(0 <= C(g, e)) ? g.push(e) : ca(e) && Da(g, e);
                f.className = g.join(" ");
                b.cardContentNode = c
            }
            return c
        };
    var Ad = function() {};
    A(Ad, vd);
    aa(Ad);
    Ad.prototype.O = "hovercard";
    var Bd = function() {};
    A(Bd, ud);
    aa(Bd);
    Bd.prototype.O = "tooltip";
    var Cd = function(a) {
        var b = L("yt-uix-tooltip-shared-mask"),
            c = b && rb(b, function(b) {
                return b == a
            }, o, 2);
        b && c && (b.parentNode.removeChild(b), gd(b), document.body.appendChild(b))
    };
    var Dd = function(a, b) {
            this.a = a;
            this.v = b || n;
            this.n = S(a, "subscription-type") || "user";
            this.R = S(a, "subscription-value") || "";
            this.wa = !!S(a, "enable-tooltip");
            this.u = !!S(a, "enable-hovercard");
            this.r = o;
            this.g()
        },
        Ed = function(a, b) {
            var c = M("yt-subscription-button-js-default", a);
            E(c, function(a) {
                S(a, "subscription-initialized") || (new Dd(a, b), R(a, "subscription-initialized", "true"))
            })
        };
    Dd.prototype.getId = function() {
        return S(this.a, "subscription-id") || n
    };
    var Gd = function(a, b) {
        b ? R(a.a, "subscription-id", b) : Hb(a.a, "subscription-id");
        Fd(a)
    };
    Dd.prototype.getValue = function() {
        return this.R
    };
    Dd.prototype.getType = function() {
        return this.n
    };
    var Fd = function(a) {
        G(a.a, "subscribed", !!a.getId());
        var b = Z(Ad.getInstance(), "target");
        G(a.a, b, !!a.getId() && a.u);
        if (a.wa) {
            var b = (a.getId() ? "un" : "") + "subscribe-tooltip",
                b = S(a.a, b) || "",
                c = Bd.getInstance(),
                a = a.a;
            c.setData(a, "tooltip-text", b);
            a = c.getData(a, "content-id");
            if (a = L(a)) a.innerHTML = b
        }
    };
    r = Dd.prototype;
    r.g = function() {
        T(this.a, "click", x(this.xa, this));
        jd("SUBSCRIBE", this.ja, this);
        jd("SUBSCRIBE", this.ca, this);
        jd("UNSUBSCRIBE", this.ja, this);
        this.u && T(this.a, "mouseover", x(this.ya, this));
        Fd(this)
    };
    r.ja = function(a, b, c) {
        c != this.getId() && this.getValue() == a && this.getType() == b && Gd(this, c)
    };
    r.ca = function() {
        if (this.u) {
            var a = Ad.getInstance(),
                b = this.a,
                c = O(b, n, Z(a));
            if (c && (b = wd(a, b, c))) Ga(c, Z(a, "active")), Ga(b, Z(a, "card-visible")), gd(b), b.cardTargetNode = n, b.cardRootNode = n
        }
    };
    r.xa = function() {
        if (this.r) return o;
        var a = Bd.getInstance(),
            b = this.a;
        if (b && (a = L(Z(a) + Kb(b)))) Cd(a), document.body.removeChild(a), Hb(b, "content-id");
        this.getId() ? Hd(this) : Id(this)
    };
    r.ya = function() {
        this.getId() && Xb(x(function() {
            Jd(this)
        }, this), 350)
    };
    var Jd = function(a) {
            var b = Ad.getInstance();
            if (!a.ea && zd(b, a.a)) {
                a.ea = l;
                var c = {
                    hovercard: 1
                };
                c["action_get_subscription_form_for_" + a.n] = 1;
                var d = {
                    session_token: Pc.subscription_ajax
                };
                d[Kd(a)] = a.R;
                Kc("/subscription_ajax", {
                    method: "POST",
                    d: c,
                    m: d,
                    Q: a,
                    f: function(a, c) {
                        var d = this.a,
                            i = c.response.html_content,
                            j = O(d, n, Z(b));
                        if (j) {
                            var m = yd(b, j);
                            m && (m.innerHTML = i, 0 <= C(F(j), Z(b, "active")) && (i = wd(b, d, j), xd(b, d, i)))
                        }
                        Ld(this)
                    },
                    h: function() {
                        this.ea = o
                    }
                })
            }
        },
        Ld = function(a) {
            var b = Ad.getInstance(),
                c = O(a.a, n, Z(b)),
                d = yd(b, c);
            E(d.getElementsByTagName("input"), function(a) {
                T(a, "change", x(function() {
                    for (var a = d.getElementsByTagName("form")[0], b = a.action || document.location.href, c = a.method.toUpperCase() || "GET", e = [], m = a.elements, k, p = 0; k = m[p]; p++)
                        if (!(k.disabled || "fieldset" == k.tagName.toLowerCase())) {
                            var s = k.name;
                            switch (k.type.toLowerCase()) {
                                case "file":
                                case "submit":
                                case "reset":
                                case "button":
                                    break;
                                case "select-multiple":
                                    k = Ac(k);
                                    if (k != n)
                                        for (var z, za = 0; z = k[za]; za++) zc(e, s, z);
                                    break;
                                default:
                                    z = Ac(k), z != n && zc(e, s, z)
                            }
                        } m = a.getElementsByTagName("input");
                    for (p = 0; k = m[p]; p++) k.form == a && "image" == k.type.toLowerCase() && (s = k.name, zc(e, s, k.value), zc(e, s + ".x", "0"), zc(e, s + ".y", "0"));
                    Ic(b, h, c, e.join("&"), h)
                }, this))
            }, a)
        },
        Id = function(a) {
            if (Wb("LOGGED_IN")) {
                var b = Kd(a),
                    c = {};
                c["action_create_subscription_to_" + a.n] = 1;
                var d = S(a.a, "subscription-feature");
                d && (c.feature = d);
                d = {
                    session_token: Pc.subscription_ajax
                };
                d[b] = a.R;
                a.r = l;
                a.a.disabled = l;
                Kc("/subscription_ajax", {
                    method: "POST",
                    Q: a,
                    d: c,
                    m: d,
                    f: function(a, b) {
                        Gd(this, b.response.id);
                        kd("SUBSCRIBE", this.getValue(), this.getType(),
                            this.getId());
                        this.u && (Ad.getInstance().show(this.a), Jd(this));
                        this.v && this.v(this.a, l)
                    },
                    l: function() {
                        this.r = o;
                        this.a.disabled = o
                    }
                });
                od("convSubscribeUrl")
            }
        },
        Hd = function(a) {
            var b = {
                    s: a.getId(),
                    session_token: Pc.subscription_ajax
                },
                c = {
                    action_remove_subscriptions: 1
                },
                d = S(a.a, "subscription-feature");
            d && (c.feature = d);
            a.r = l;
            a.a.disabled = l;
            Kc("/subscription_ajax", {
                method: "POST",
                Q: a,
                d: c,
                m: b,
                f: function() {
                    Gd(this, n);
                    this.ca();
                    kd("UNSUBSCRIBE", this.getValue(), this.getType(), n);
                    this.v && this.v(this.a, o)
                },
                l: function() {
                    this.r =
                        o;
                    this.a.disabled = o
                }
            });
            od("convUnsubscribeUrl")
        },
        Kd = function(a) {
            return "playlist" == a.n ? "p" : "blog" == a.n ? "b" : "topic" == a.n ? "l" : "u"
        };
    var Md = {},
        Nd = function(a, b) {
            var a = L(a),
                c = b || a[da] || (a[da] = ++ea),
                d = Md[c];
            d && (Md[c] = ua(d, function(b) {
                return b[0] != a
            }))
        };
    var Pd = function(a) {
            Od(function(a, c) {
                var d = S(a, "group-key");
                d && (Nd(a, d), Hb(a, "group-key"));
                a.src = c
            }, a)
        },
        Od = function(a, b) {
            var c = ob("img", n, b);
            E(c, function(b) {
                var c = S(b, "thumb");
                c && a.call(t, b, c)
            })
        };
    var Qd = {},
        Rd = function(a) {
            if (Wb("EVENTS_TRACKER_INSTALLED")) {
                var b = Qd.feed_item_expanded;
                if (!b) {
                    var c = window._gaq._getAsyncTracker("eventsPageTracker");
                    if (!c) return;
                    window._gaq.push(function() {
                        b = c._createEventTracker("feed_item_expanded");
                        Qd.feed_item_expanded = b
                    })
                }
                window._gaq.push(function() {
                    b._trackEvent(a, h, h)
                })
            }
        };
    var Sd, Td, Ud, Vd, Wd, Xd = function(a) {
        var a = a.currentTarget,
            b = P(a, "feed-item"),
            c = !(0 <= C(F(b), "expanded"));
        G(b, "expanded", c);
        c && (Pd(b), b = S(a, "num-aggregated-actions") || 0, c = S(a, "num-aggregated-users") || 0, a = S(a, "num-aggregated-videos") || 0, a = Ec({
            actions: b,
            users: c,
            videos: a
        }), Rd(a || "null"), a = "a=feed_item_expanded" + (a ? "&" + a : "").replace(/\//g, "&"), nd("/gen_204?" + a, h))
    };
    var Yd = function(a) {
            var a = a.ua(),
                b;
            for (b in a) {
                var c = L(b + "-connected");
                if (c) {
                    var d = a[b],
                        e = L(b + "-not-connected"),
                        f = L(b + "-display-name"),
                        g = d.is_connected;
                    ed(c, g);
                    ed(e, !g);
                    c = f;
                    d = d.connected_as || "";
                    if ("textContent" in c) c.textContent = d;
                    else if (c.firstChild && 3 == c.firstChild.nodeType) {
                        for (; c.lastChild != c.firstChild;) c.removeChild(c.lastChild);
                        c.firstChild.data = d
                    } else {
                        e = c;
                        for (f = h; f = e.firstChild;) e.removeChild(f);
                        c.appendChild(K(c).createTextNode(d))
                    }
                }
            }
        },
        $d = function(a, b) {
            if (b) {
                var c = O(a, "li");
                Zd(c)
            }
        },
        ae = function(a) {
            var b =
                P(a.currentTarget, "guide-recommendation-item"),
                a = 0 <= C(F(b), "featured"),
                c = S(b, "external-id"),
                d = O(b, "li"),
                b = O(d, "ul"),
                b = M("guide-recommendation-item", b),
                b = va(b, function(a) {
                    return S(a, "external-id")
                }),
                e = {};
            a && (e.featured = 1);
            Kc("/guide_ajax.php?action_dismiss_channel=1", {
                method: "POST",
                d: e,
                m: {
                    session_token: Pc.guide_ajax,
                    dismissed_id: c,
                    shown_ids: b.join()
                },
                f: function(a, b) {
                    var c = b.new_suggested_html;
                    if (c) {
                        var c = Mb(c),
                            e = d.parentNode;
                        e && e.replaceChild(c, d);
                        Pd(c);
                        Ed(c, $d);
                        xc(c, {
                            duration: 0.3
                        })
                    } else Zd(d)
                },
                h: function() {
                    Zd(d)
                }
            })
        },
        be = function(a) {
            a.preventDefault();
            var b = P(a.currentTarget, "recommended-video-item"),
                a = S(b, "video-id");
            Kc("/guide_ajax.php?action_dismiss_video=1", {
                method: "POST",
                m: {
                    session_token: Pc.guide_ajax,
                    video_id: a
                },
                f: function() {
                    Zd(b)
                },
                h: function() {
                    Zd(b)
                }
            })
        },
        Zd = function(a) {
            yc(a, {
                duration: 0.3,
                l: function() {
                    qb(a)
                }
            })
        },
        ee = function(a) {
            O(a.target, n, "guide-item-action") || (a = ce(a.currentTarget), de(a.Ea, a.Fa))
        },
        ce = function(a) {
            var b = S(a, "feed-name") || n,
                c = S(a, "feed-type") || n,
                a = M("guide-item", Sd);
            E(a, function(a) {
                var e = S(a, "feed-name"),
                    f = S(a, "feed-type"),
                    e = b && e == b && f == c;
                G(a, "selected", e);
                (a = P(a, "guide-item-container")) && G(a, "selected-child", e)
            });
            return {
                Fa: c,
                Ea: b
            }
        },
        ge = function(a) {
            var a = P(a.currentTarget, "feed-container"),
                b = P(a, "individual-feed"),
                c = S(b, "feed-name") || n,
                b = S(b, "feed-type") || n,
                d = S(a, "filter-type") || n,
                e = S(a, "view-type") || n,
                f = S(a, "paging") || n;
            fe(a, c, b, d, e, f)
        },
        ie = function(a) {
            var b = P(a.currentTarget, "individual-feed"),
                c = N("feed-view-button", b),
                d = S(b, "feed-name") || n,
                e = S(b, "feed-type") || n,
                a = a.currentTarget.checked ? "u" : n,
                c =
                c && S(c, "view-type") || n,
                f = Bb.getInstance();
            "u" == a ? (Eb(Fb.qa, o), Eb(Fb.ra, l)) : (Eb(Fb.qa, o), Eb(Fb.ra, o));
            f.save();
            he(b, d, e, a, c)
        },
        je = function(a) {
            var b = P(a.currentTarget, "individual-feed"),
                c = S(b, "feed-name") || n,
                d = S(b, "feed-type") || n,
                e = S(a.currentTarget, "filter-type") || n,
                a = M("user-feed-filter", b);
            E(a, function(a) {
                var b = S(a, "filter-type") || n;
                G(a, "selected", e == b)
            });
            he(b, c, d, e, n)
        },
        ke = function(a) {
            var b = S(a.currentTarget, "feed-name") || n,
                c = S(a.currentTarget, "feed-type") || n,
                d = L(["feed", c, b].join("-")),
                e = N("feed-view-button",
                    d),
                f = N("feed-filter", d).checked ? "u" : n,
                g = S(a.currentTarget, "view-type") || n;
            R(e, "view-type", g || "");
            g ? zb("feed_view", g || "") : Ab();
            a = M("feed-view-choice");
            E(a, function(a) {
                var b = S(a, "view-type") || n;
                G(a, "checked", g == b)
            });
            he(d, b, c, f, g)
        },
        he = function(a, b, c, d, e) {
            var f = M("feed-container", a);
            E(f, gd);
            gd(Wd);
            R(a, "last-clicked-filter", d || "");
            R(a, "last-clicked-view", e || "");
            if (f = wa(f, function(a) {
                    var b = S(a, "filter-type") || n,
                        a = S(a, "view-type") || n;
                    return b == d && a == e
                })) fd(f), le(a);
            else {
                var g = document.createElement("div");
                g.className = "feed-container";
                g.innerHTML = Vd.innerHTML;
                R(g, "filter-type", d || "");
                a.appendChild(g);
                b = me(b, c, d, e);
                Kc(b.url, {
                    d: b.d,
                    format: "JSON",
                    f: function(b, c) {
                        var f = Mb(c.feed_html),
                            k = N("before-feed-content", a);
                        k.parentNode && k.parentNode.insertBefore(f, k.nextSibling);
                        Pd(f);
                        qb(g);
                        c.paging && R(f, "paging", c.paging);
                        var k = S(a, "last-clicked-filter") || n,
                            p = S(a, "last-clicked-view") || n;
                        (k != d || p != e) && gd(f);
                        gd(Wd)
                    },
                    h: function() {
                        qb(g);
                        fd(Wd)
                    }
                })
            }
        },
        ne = function() {
            var a = M("individual-feed", Ud);
            E(a, gd)
        },
        de = function(a,
            b) {
            ne();
            var c = ["feed", b, a].join("-"),
                d = L(c);
            R(Sd, "last-clicked-item", c);
            if (d) le(d);
            else {
                d = document.createElement("div");
                d.id = c;
                d.className = "individual-feed";
                d.innerHTML = Vd.innerHTML;
                R(d, "feed-name", a || "");
                R(d, "feed-type", b || "");
                Ud.appendChild(d);
                var e = me(a, b);
                Kc(e.url, {
                    d: e.d,
                    format: "JSON",
                    f: function(a, b) {
                        d.innerHTML = b.feed_html;
                        Pd(d);
                        Ed(d);
                        S(Sd, "last-clicked-item") == c && le(d)
                    },
                    h: function() {
                        fd(Wd);
                        qb(d)
                    }
                })
            }
        },
        fe = function(a, b, c, d, e, f) {
            var c = me(b, c, d, e, f),
                b = c.url,
                c = c.d,
                g = N("feed-load-more-container", a);
            Ea(g, "loading");
            Kc(b, {
                d: c,
                format: "JSON",
                f: function(b, c) {
                    var d = Mb(c.feed_html);
                    g.parentNode && g.parentNode.insertBefore(d, g);
                    Pd(d);
                    Ga(g, "loading");
                    c.paging ? R(a, "paging", c.paging) : gd(g);
                    gd(Wd)
                },
                h: function() {
                    Ga(g, "loading");
                    fd(Wd)
                }
            })
        },
        le = function(a) {
            xc(a, {
                duration: 0.5
            });
            fd(a)
        },
        me = function(a, b, c, d, e) {
            var f = "",
                g = {};
            "blog" == b ? (f = "/guide_ajax.php?action_load_blog_feed=1", g = {
                blog_id: a
            }) : "chart" == b ? (f = "/guide_ajax.php?action_load_chart_feed=1", g = {
                chart_name: a
            }) : "personal" == b ? (f = "/guide_ajax.php?action_load_personal_feed=1",
                g = {
                    feed_type: a
                }) : "show" == b ? (f = "/guide_ajax.php?action_load_show_feed=1", g = {
                show_id: a
            }) : "social" == b ? (f = "/guide_ajax.php?action_load_social_feed=1", g = {
                feed_type: a
            }) : "system" == b ? (f = "/guide_ajax.php?action_load_system_feed=1", g = {
                feed_type: a
            }) : "main" == b ? f = "/guide_ajax.php?action_load_main_feed=1" : "topic" == b ? (f = "/guide_ajax.php?action_load_topic_feed=1", g = {
                topic_id: a
            }) : "user" == b && (f = "/guide_ajax.php?action_load_user_feed=1", g = {
                user_id: a
            });
            c && (g.filter_type = c);
            d && (g.view_type = d);
            e && (g.paging = e);
            return {
                url: f,
                d: g
            }
        };
    var pe = function(a) {
            this.sa = a;
            a = n;
            a = oe(this.sa);
            a = ja("__%s__", "(" + a.join("|") + ")");
            this.Da = RegExp(a, "g")
        },
        qe = /__([a-z]+(?:_[a-z]+)*)__/g,
        re = function(a) {
            a = L(a).innerHTML;
            a = a.replace(/^\s*(<\!--\s*)?/, "");
            a = a.replace(/(\s*--\>)?\s*$/, "");
            return new pe(a)
        },
        oe = function(a) {
            var b = [],
                c = {};
            a.replace(qe, function(a, e) {
                e in c || (c[e] = l, b.push(e))
            });
            return b
        },
        se = function(a, b) {
            return a.sa.replace(a.Da, x(function(a, d) {
                return ra(b[d] || "")
            }, a))
        };
    var te = function() {};
    A(te, ud);
    aa(te);
    te.prototype.O = "button";
    var ue, ve, we, xe, ye, ze = function(a) {
            a = {
                sort: S(a.target, "sort-type")
            };
            a = Cc(Dc(["/subscription_manager/friends"], a));
            window.location = Cc(Dc([a], {})) + ""
        },
        Ae = function(a) {
            if (!O(a.target, "button") && !O(a.target, "a") && (a = S(a.currentTarget, "href"))) window.location = Cc(Dc([a], {})) + ""
        },
        Ce = function(a, b) {
            var c = P(a, "subscription-item"),
                d = !b;
            G(c, "unsubscribed", d);
            d && 0 <= C(F(c), "pinned") && (G(c, "pinned", o), Be())
        },
        De = function(a) {
            var b = P(a.currentTarget, "subscription-item");
            if (!S(b, "loading")) {
                R(b, "loading", "true");
                var a =
                    S(b, "subscription-id"),
                    c = !(0 <= C(F(b), "pinned"));
                if (!c || M("pinned", ue).length < ye) {
                    G(b, "pinned", c);
                    Be();
                    var d = {};
                    c && (d.pinned = "true");
                    Kc("/subscription_ajax?action_update_subscription_pinned=1", {
                        format: "JSON",
                        method: "POST",
                        d: d,
                        m: {
                            session_token: Pc.subscription_ajax,
                            subscription_id: a
                        },
                        h: function() {
                            G(b, "pinned", !c);
                            Be()
                        },
                        l: function() {
                            Hb(b, "loading")
                        }
                    })
                }
            }
        },
        Be = function() {
            var a = M("pinned", ue),
                b = (a || M("pinned", ue)).length < ye;
            G(ue, "can-pin-more", b);
            for (var b = va(a, function(a) {
                    var b = S(a, "subscription-id"),
                        c =
                        N("subscription-title", a).innerHTML,
                        a = a.getElementsByTagName("img")[0],
                        a = S(a, "thumb") || a.src;
                    return se(we, {
                        subscription_id: b,
                        display_name: c,
                        profile_image_url: a
                    })
                }), a = ye - a.length, c = se(xe, {}), d = 0; d < a; d++) b.push(c);
            ve.innerHTML = b.join("")
        };
    y("yt.www.guide.init", function() {
        Sd = N("guide");
        U(Sd, ee, "guide-item");
        Ed(Sd, $d);
        U(Sd, ae, "guide-subscription-dismiss");
        Ud = L("feed");
        Vd = L("feed-loading-template");
        Wd = L("feed-error");
        U(Ud, ge, "feed-load-more");
        U(Ud, ie, "feed-filter");
        U(Ud, je, "user-feed-filter");
        var a = N("yt-uix-button-menu-short", Ud);
        U(a, ke, "feed-view-choice");
        Td = L("video-sidebar");
        U(Td, be, "recommended-video-dismiss");
        U(Ud, Xd, "feed-item-show-aggregate")
    });
    y("yt.www.guide.initAutoshare", function(a, b) {
        var c = new W(Pc.autoshare, b, 0, n, l);
        c.oa(Yd);
        var d = L("facebook-connect-button");
        d && c.N(d, "facebook", l);
        (d = L("twitter-connect-button")) && c.N(d, "twitter", l);
        (d = L("orkut-connect-button")) && c.N(d, "orkut", l);
        window.autoshare = c
    });
    y("yt.www.guide.loadSocialPanel", function() {
        ce(L("social-guide-item"));
        de("connect", "social")
    });
    y("yt.www.guide.subscriptionmanager.init", function() {
        ue = L("subscription-manager-list");
        U(ue, De, "subscription-pin");
        U(ue, Ae, "subscription-item");
        var a = L("sort-button");
        if (a) {
            var b = te.getInstance();
            if (!a.widgetMenu) {
                var c = b.getData(a, "button-menu-id"),
                    c = c && L(c),
                    b = Z(b, "menu");
                c ? Ea(c, b) : c = N(b, a);
                a.widgetMenu = c
            }
            U(a.widgetMenu, ze, "friend-sort")
        }
        if (ve = a = L("pinned-subscriptions")) a = S(a, "max-pinned"), ye = parseInt(a, 10), a = L("pinned-channel-template"), we = re(a), a = L("pinned-channel-placeholder-template"), xe = re(a);
        Ed(ue, Ce)
    });
})();
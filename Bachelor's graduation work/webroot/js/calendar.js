//     RUS Calendar Responsive Bootstrap
//     Author: BootstrapTema
//     http://bootstraptema.ru
//     Underscore.js 1.6.0
//     http://underscorejs.org
//     (c) 2009-2014 Jeremy Ashkenas, DocumentCloud and Investigative Reporters & Editors
//     Underscore may be freely distributed under the MIT license.
(function() { var n = this,
        t = n._,
        r = {},
        e = Array.prototype,
        u = Object.prototype,
        i = Function.prototype,
        a = e.push,
        o = e.slice,
        c = e.concat,
        l = u.toString,
        f = u.hasOwnProperty,
        s = e.forEach,
        p = e.map,
        h = e.reduce,
        v = e.reduceRight,
        g = e.filter,
        d = e.every,
        m = e.some,
        y = e.indexOf,
        b = e.lastIndexOf,
        x = Array.isArray,
        w = Object.keys,
        _ = i.bind,
        j = function(n) { return n instanceof j ? n : this instanceof j ? void(this._wrapped = n) : new j(n) }; "undefined" != typeof exports ? ("undefined" != typeof module && module.exports && (exports = module.exports = j), exports._ = j) : n._ = j, j.VERSION = "1.6.0"; var A = j.each = j.forEach = function(n, t, e) { if (null == n) return n; if (s && n.forEach === s) n.forEach(t, e);
        else if (n.length === +n.length) { for (var u = 0, i = n.length; i > u; u++)
                if (t.call(e, n[u], u, n) === r) return } else
            for (var a = j.keys(n), u = 0, i = a.length; i > u; u++)
                if (t.call(e, n[a[u]], a[u], n) === r) return; return n };
    j.map = j.collect = function(n, t, r) { var e = []; return null == n ? e : p && n.map === p ? n.map(t, r) : (A(n, function(n, u, i) { e.push(t.call(r, n, u, i)) }), e) }; var O = "Reduce of empty array with no initial value";
    j.reduce = j.foldl = j.inject = function(n, t, r, e) { var u = arguments.length > 2; if (null == n && (n = []), h && n.reduce === h) return e && (t = j.bind(t, e)), u ? n.reduce(t, r) : n.reduce(t); if (A(n, function(n, i, a) { u ? r = t.call(e, r, n, i, a) : (r = n, u = !0) }), !u) throw new TypeError(O); return r }, j.reduceRight = j.foldr = function(n, t, r, e) { var u = arguments.length > 2; if (null == n && (n = []), v && n.reduceRight === v) return e && (t = j.bind(t, e)), u ? n.reduceRight(t, r) : n.reduceRight(t); var i = n.length; if (i !== +i) { var a = j.keys(n);
            i = a.length } if (A(n, function(o, c, l) { c = a ? a[--i] : --i, u ? r = t.call(e, r, n[c], c, l) : (r = n[c], u = !0) }), !u) throw new TypeError(O); return r }, j.find = j.detect = function(n, t, r) { var e; return k(n, function(n, u, i) { return t.call(r, n, u, i) ? (e = n, !0) : void 0 }), e }, j.filter = j.select = function(n, t, r) { var e = []; return null == n ? e : g && n.filter === g ? n.filter(t, r) : (A(n, function(n, u, i) { t.call(r, n, u, i) && e.push(n) }), e) }, j.reject = function(n, t, r) { return j.filter(n, function(n, e, u) { return !t.call(r, n, e, u) }, r) }, j.every = j.all = function(n, t, e) { t || (t = j.identity); var u = !0; return null == n ? u : d && n.every === d ? n.every(t, e) : (A(n, function(n, i, a) { return (u = u && t.call(e, n, i, a)) ? void 0 : r }), !!u) }; var k = j.some = j.any = function(n, t, e) { t || (t = j.identity); var u = !1; return null == n ? u : m && n.some === m ? n.some(t, e) : (A(n, function(n, i, a) { return u || (u = t.call(e, n, i, a)) ? r : void 0 }), !!u) };
    j.contains = j.include = function(n, t) { return null == n ? !1 : y && n.indexOf === y ? n.indexOf(t) != -1 : k(n, function(n) { return n === t }) }, j.invoke = function(n, t) { var r = o.call(arguments, 2),
            e = j.isFunction(t); return j.map(n, function(n) { return (e ? t : n[t]).apply(n, r) }) }, j.pluck = function(n, t) { return j.map(n, j.property(t)) }, j.where = function(n, t) { return j.filter(n, j.matches(t)) }, j.findWhere = function(n, t) { return j.find(n, j.matches(t)) }, j.max = function(n, t, r) { if (!t && j.isArray(n) && n[0] === +n[0] && n.length < 65535) return Math.max.apply(Math, n); var e = -1 / 0,
            u = -1 / 0; return A(n, function(n, i, a) { var o = t ? t.call(r, n, i, a) : n;
            o > u && (e = n, u = o) }), e }, j.min = function(n, t, r) { if (!t && j.isArray(n) && n[0] === +n[0] && n.length < 65535) return Math.min.apply(Math, n); var e = 1 / 0,
            u = 1 / 0; return A(n, function(n, i, a) { var o = t ? t.call(r, n, i, a) : n;
            u > o && (e = n, u = o) }), e }, j.shuffle = function(n) { var t, r = 0,
            e = []; return A(n, function(n) { t = j.random(r++), e[r - 1] = e[t], e[t] = n }), e }, j.sample = function(n, t, r) { return null == t || r ? (n.length !== +n.length && (n = j.values(n)), n[j.random(n.length - 1)]) : j.shuffle(n).slice(0, Math.max(0, t)) }; var E = function(n) { return null == n ? j.identity : j.isFunction(n) ? n : j.property(n) };
    j.sortBy = function(n, t, r) { return t = E(t), j.pluck(j.map(n, function(n, e, u) { return { value: n, index: e, criteria: t.call(r, n, e, u) } }).sort(function(n, t) { var r = n.criteria,
                e = t.criteria; if (r !== e) { if (r > e || r === void 0) return 1; if (e > r || e === void 0) return -1 } return n.index - t.index }), "value") }; var F = function(n) { return function(t, r, e) { var u = {}; return r = E(r), A(t, function(i, a) { var o = r.call(e, i, a, t);
                n(u, o, i) }), u } };
    j.groupBy = F(function(n, t, r) { j.has(n, t) ? n[t].push(r) : n[t] = [r] }), j.indexBy = F(function(n, t, r) { n[t] = r }), j.countBy = F(function(n, t) { j.has(n, t) ? n[t]++ : n[t] = 1 }), j.sortedIndex = function(n, t, r, e) { r = E(r); for (var u = r.call(e, t), i = 0, a = n.length; a > i;) { var o = i + a >>> 1;
            r.call(e, n[o]) < u ? i = o + 1 : a = o } return i }, j.toArray = function(n) { return n ? j.isArray(n) ? o.call(n) : n.length === +n.length ? j.map(n, j.identity) : j.values(n) : [] }, j.size = function(n) { return null == n ? 0 : n.length === +n.length ? n.length : j.keys(n).length }, j.first = j.head = j.take = function(n, t, r) { return null == n ? void 0 : null == t || r ? n[0] : 0 > t ? [] : o.call(n, 0, t) }, j.initial = function(n, t, r) { return o.call(n, 0, n.length - (null == t || r ? 1 : t)) }, j.last = function(n, t, r) { return null == n ? void 0 : null == t || r ? n[n.length - 1] : o.call(n, Math.max(n.length - t, 0)) }, j.rest = j.tail = j.drop = function(n, t, r) { return o.call(n, null == t || r ? 1 : t) }, j.compact = function(n) { return j.filter(n, j.identity) }; var M = function(n, t, r) { return t && j.every(n, j.isArray) ? c.apply(r, n) : (A(n, function(n) { j.isArray(n) || j.isArguments(n) ? t ? a.apply(r, n) : M(n, t, r) : r.push(n) }), r) };
    j.flatten = function(n, t) { return M(n, t, []) }, j.without = function(n) { return j.difference(n, o.call(arguments, 1)) }, j.partition = function(n, t) { var r = [],
            e = []; return A(n, function(n) {
            (t(n) ? r : e).push(n) }), [r, e] }, j.uniq = j.unique = function(n, t, r, e) { j.isFunction(t) && (e = r, r = t, t = !1); var u = r ? j.map(n, r, e) : n,
            i = [],
            a = []; return A(u, function(r, e) {
            (t ? e && a[a.length - 1] === r : j.contains(a, r)) || (a.push(r), i.push(n[e])) }), i }, j.union = function() { return j.uniq(j.flatten(arguments, !0)) }, j.intersection = function(n) { var t = o.call(arguments, 1); return j.filter(j.uniq(n), function(n) { return j.every(t, function(t) { return j.contains(t, n) }) }) }, j.difference = function(n) { var t = c.apply(e, o.call(arguments, 1)); return j.filter(n, function(n) { return !j.contains(t, n) }) }, j.zip = function() { for (var n = j.max(j.pluck(arguments, "length").concat(0)), t = new Array(n), r = 0; n > r; r++) t[r] = j.pluck(arguments, "" + r); return t }, j.object = function(n, t) { if (null == n) return {}; for (var r = {}, e = 0, u = n.length; u > e; e++) t ? r[n[e]] = t[e] : r[n[e][0]] = n[e][1]; return r }, j.indexOf = function(n, t, r) { if (null == n) return -1; var e = 0,
            u = n.length; if (r) { if ("number" != typeof r) return e = j.sortedIndex(n, t), n[e] === t ? e : -1;
            e = 0 > r ? Math.max(0, u + r) : r } if (y && n.indexOf === y) return n.indexOf(t, r); for (; u > e; e++)
            if (n[e] === t) return e;
        return -1 }, j.lastIndexOf = function(n, t, r) { if (null == n) return -1; var e = null != r; if (b && n.lastIndexOf === b) return e ? n.lastIndexOf(t, r) : n.lastIndexOf(t); for (var u = e ? r : n.length; u--;)
            if (n[u] === t) return u;
        return -1 }, j.range = function(n, t, r) { arguments.length <= 1 && (t = n || 0, n = 0), r = arguments[2] || 1; for (var e = Math.max(Math.ceil((t - n) / r), 0), u = 0, i = new Array(e); e > u;) i[u++] = n, n += r; return i }; var R = function() {};
    j.bind = function(n, t) { var r, e; if (_ && n.bind === _) return _.apply(n, o.call(arguments, 1)); if (!j.isFunction(n)) throw new TypeError; return r = o.call(arguments, 2), e = function() { if (!(this instanceof e)) return n.apply(t, r.concat(o.call(arguments)));
            R.prototype = n.prototype; var u = new R;
            R.prototype = null; var i = n.apply(u, r.concat(o.call(arguments))); return Object(i) === i ? i : u } }, j.partial = function(n) { var t = o.call(arguments, 1); return function() { for (var r = 0, e = t.slice(), u = 0, i = e.length; i > u; u++) e[u] === j && (e[u] = arguments[r++]); for (; r < arguments.length;) e.push(arguments[r++]); return n.apply(this, e) } }, j.bindAll = function(n) { var t = o.call(arguments, 1); if (0 === t.length) throw new Error("bindAll must be passed function names"); return A(t, function(t) { n[t] = j.bind(n[t], n) }), n }, j.memoize = function(n, t) { var r = {}; return t || (t = j.identity),
            function() { var e = t.apply(this, arguments); return j.has(r, e) ? r[e] : r[e] = n.apply(this, arguments) } }, j.delay = function(n, t) { var r = o.call(arguments, 2); return setTimeout(function() { return n.apply(null, r) }, t) }, j.defer = function(n) { return j.delay.apply(j, [n, 1].concat(o.call(arguments, 1))) }, j.throttle = function(n, t, r) { var e, u, i, a = null,
            o = 0;
        r || (r = {}); var c = function() { o = r.leading === !1 ? 0 : j.now(), a = null, i = n.apply(e, u), e = u = null }; return function() { var l = j.now();
            o || r.leading !== !1 || (o = l); var f = t - (l - o); return e = this, u = arguments, 0 >= f ? (clearTimeout(a), a = null, o = l, i = n.apply(e, u), e = u = null) : a || r.trailing === !1 || (a = setTimeout(c, f)), i } }, j.debounce = function(n, t, r) { var e, u, i, a, o, c = function() { var l = j.now() - a;
            t > l ? e = setTimeout(c, t - l) : (e = null, r || (o = n.apply(i, u), i = u = null)) }; return function() { i = this, u = arguments, a = j.now(); var l = r && !e; return e || (e = setTimeout(c, t)), l && (o = n.apply(i, u), i = u = null), o } }, j.once = function(n) { var t, r = !1; return function() { return r ? t : (r = !0, t = n.apply(this, arguments), n = null, t) } }, j.wrap = function(n, t) { return j.partial(t, n) }, j.compose = function() { var n = arguments; return function() { for (var t = arguments, r = n.length - 1; r >= 0; r--) t = [n[r].apply(this, t)]; return t[0] } }, j.after = function(n, t) { return function() { return --n < 1 ? t.apply(this, arguments) : void 0 } }, j.keys = function(n) { if (!j.isObject(n)) return []; if (w) return w(n); var t = []; for (var r in n) j.has(n, r) && t.push(r); return t }, j.values = function(n) { for (var t = j.keys(n), r = t.length, e = new Array(r), u = 0; r > u; u++) e[u] = n[t[u]]; return e }, j.pairs = function(n) { for (var t = j.keys(n), r = t.length, e = new Array(r), u = 0; r > u; u++) e[u] = [t[u], n[t[u]]]; return e }, j.invert = function(n) { for (var t = {}, r = j.keys(n), e = 0, u = r.length; u > e; e++) t[n[r[e]]] = r[e]; return t }, j.functions = j.methods = function(n) { var t = []; for (var r in n) j.isFunction(n[r]) && t.push(r); return t.sort() }, j.extend = function(n) { return A(o.call(arguments, 1), function(t) { if (t)
                for (var r in t) n[r] = t[r] }), n }, j.pick = function(n) { var t = {},
            r = c.apply(e, o.call(arguments, 1)); return A(r, function(r) { r in n && (t[r] = n[r]) }), t }, j.omit = function(n) { var t = {},
            r = c.apply(e, o.call(arguments, 1)); for (var u in n) j.contains(r, u) || (t[u] = n[u]); return t }, j.defaults = function(n) { return A(o.call(arguments, 1), function(t) { if (t)
                for (var r in t) n[r] === void 0 && (n[r] = t[r]) }), n }, j.clone = function(n) { return j.isObject(n) ? j.isArray(n) ? n.slice() : j.extend({}, n) : n }, j.tap = function(n, t) { return t(n), n }; var S = function(n, t, r, e) { if (n === t) return 0 !== n || 1 / n == 1 / t; if (null == n || null == t) return n === t;
        n instanceof j && (n = n._wrapped), t instanceof j && (t = t._wrapped); var u = l.call(n); if (u != l.call(t)) return !1; switch (u) {
            case "[object String]":
                return n == String(t);
            case "[object Number]":
                return n != +n ? t != +t : 0 == n ? 1 / n == 1 / t : n == +t;
            case "[object Date]":
            case "[object Boolean]":
                return +n == +t;
            case "[object RegExp]":
                return n.source == t.source && n.global == t.global && n.multiline == t.multiline && n.ignoreCase == t.ignoreCase } if ("object" != typeof n || "object" != typeof t) return !1; for (var i = r.length; i--;)
            if (r[i] == n) return e[i] == t;
        var a = n.constructor,
            o = t.constructor; if (a !== o && !(j.isFunction(a) && a instanceof a && j.isFunction(o) && o instanceof o) && "constructor" in n && "constructor" in t) return !1;
        r.push(n), e.push(t); var c = 0,
            f = !0; if ("[object Array]" == u) { if (c = n.length, f = c == t.length)
                for (; c-- && (f = S(n[c], t[c], r, e));); } else { for (var s in n)
                if (j.has(n, s) && (c++, !(f = j.has(t, s) && S(n[s], t[s], r, e)))) break;
            if (f) { for (s in t)
                    if (j.has(t, s) && !c--) break;
                f = !c } } return r.pop(), e.pop(), f };
    j.isEqual = function(n, t) { return S(n, t, [], []) }, j.isEmpty = function(n) { if (null == n) return !0; if (j.isArray(n) || j.isString(n)) return 0 === n.length; for (var t in n)
            if (j.has(n, t)) return !1;
        return !0 }, j.isElement = function(n) { return !(!n || 1 !== n.nodeType) }, j.isArray = x || function(n) { return "[object Array]" == l.call(n) }, j.isObject = function(n) { return n === Object(n) }, A(["Arguments", "Function", "String", "Number", "Date", "RegExp"], function(n) { j["is" + n] = function(t) { return l.call(t) == "[object " + n + "]" } }), j.isArguments(arguments) || (j.isArguments = function(n) { return !(!n || !j.has(n, "callee")) }), "function" != typeof /./ && (j.isFunction = function(n) { return "function" == typeof n }), j.isFinite = function(n) { return isFinite(n) && !isNaN(parseFloat(n)) }, j.isNaN = function(n) { return j.isNumber(n) && n != +n }, j.isBoolean = function(n) { return n === !0 || n === !1 || "[object Boolean]" == l.call(n) }, j.isNull = function(n) { return null === n }, j.isUndefined = function(n) { return n === void 0 }, j.has = function(n, t) { return f.call(n, t) }, j.noConflict = function() { return n._ = t, this }, j.identity = function(n) { return n }, j.constant = function(n) { return function() { return n } }, j.property = function(n) { return function(t) { return t[n] } }, j.matches = function(n) { return function(t) { if (t === n) return !0; for (var r in n)
                if (n[r] !== t[r]) return !1;
            return !0 } }, j.times = function(n, t, r) { for (var e = Array(Math.max(0, n)), u = 0; n > u; u++) e[u] = t.call(r, u); return e }, j.random = function(n, t) { return null == t && (t = n, n = 0), n + Math.floor(Math.random() * (t - n + 1)) }, j.now = Date.now || function() { return (new Date).getTime() }; var T = { escape: { "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#x27;" } };
    T.unescape = j.invert(T.escape); var I = { escape: new RegExp("[" + j.keys(T.escape).join("") + "]", "g"), unescape: new RegExp("(" + j.keys(T.unescape).join("|") + ")", "g") };
    j.each(["escape", "unescape"], function(n) { j[n] = function(t) { return null == t ? "" : ("" + t).replace(I[n], function(t) { return T[n][t] }) } }), j.result = function(n, t) { if (null == n) return void 0; var r = n[t]; return j.isFunction(r) ? r.call(n) : r }, j.mixin = function(n) { A(j.functions(n), function(t) { var r = j[t] = n[t];
            j.prototype[t] = function() { var n = [this._wrapped]; return a.apply(n, arguments), z.call(this, r.apply(j, n)) } }) }; var N = 0;
    j.uniqueId = function(n) { var t = ++N + ""; return n ? n + t : t }, j.templateSettings = { evaluate: /<%([\s\S]+?)%>/g, interpolate: /<%=([\s\S]+?)%>/g, escape: /<%-([\s\S]+?)%>/g }; var q = /(.)^/,
        B = { "'": "'", "\\": "\\", "\r": "r", "\n": "n", "	": "t", "\u2028": "u2028", "\u2029": "u2029" },
        D = /\\|'|\r|\n|\t|\u2028|\u2029/g;
    j.template = function(n, t, r) { var e;
        r = j.defaults({}, r, j.templateSettings); var u = new RegExp([(r.escape || q).source, (r.interpolate || q).source, (r.evaluate || q).source].join("|") + "|$", "g"),
            i = 0,
            a = "__p+='";
        n.replace(u, function(t, r, e, u, o) { return a += n.slice(i, o).replace(D, function(n) { return "\\" + B[n] }), r && (a += "'+\n((__t=(" + r + "))==null?'':_.escape(__t))+\n'"), e && (a += "'+\n((__t=(" + e + "))==null?'':__t)+\n'"), u && (a += "';\n" + u + "\n__p+='"), i = o + t.length, t }), a += "';\n", r.variable || (a = "with(obj||{}){\n" + a + "}\n"), a = "var __t,__p='',__j=Array.prototype.join," + "print=function(){__p+=__j.call(arguments,'');};\n" + a + "return __p;\n"; try { e = new Function(r.variable || "obj", "_", a) } catch (o) { throw o.source = a, o } if (t) return e(t, j); var c = function(n) { return e.call(this, n, j) }; return c.source = "function(" + (r.variable || "obj") + "){\n" + a + "}", c }, j.chain = function(n) { return j(n).chain() }; var z = function(n) { return this._chain ? j(n).chain() : n };
    j.mixin(j), A(["pop", "push", "reverse", "shift", "sort", "splice", "unshift"], function(n) { var t = e[n];
        j.prototype[n] = function() { var r = this._wrapped; return t.apply(r, arguments), "shift" != n && "splice" != n || 0 !== r.length || delete r[0], z.call(this, r) } }), A(["concat", "join", "slice"], function(n) { var t = e[n];
        j.prototype[n] = function() { return z.call(this, t.apply(this._wrapped, arguments)) } }), j.extend(j.prototype, { chain: function() { return this._chain = !0, this }, value: function() { return this._wrapped } }), "function" == typeof define && define.amd && define("underscore", [], function() { return j }) }).call(this);
//# sourceMappingURL=underscore-min.map

/**
 *               ~ CLNDR v1.4.7 ~
 * ==============================================
 *       https://github.com/kylestetz/CLNDR
 * ==============================================
 *  Created by kyle stetz (github.com/kylestetz)
 *       & available under the MIT license
 * http://opensource.org/licenses/mit-license.php
 * ==============================================
 *
 * This is the fully-commented development version of CLNDR.
 * For the production version, check out clndr.min.js
 * at https://github.com/kylestetz/CLNDR
 *
 * This work is based on the
 * jQuery lightweight plugin boilerplate
 * Original author: @ajpiano
 * Further changes, comments: @addyosmani
 * Licensed under the MIT license
 */
(function (factory) {
    // Multiple loading methods are supported depending on
    // what is available globally. While moment is loaded
    // here, the instance can be passed in at config time.
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['jquery', 'moment'], factory);
    }
    else if (typeof exports === 'object') {
        // Node/CommonJS
        factory(require('jquery'), require('moment'));
    }
    else {
        // Browser globals
        factory(jQuery, moment);
    }
}(function ($, moment) {
    // Namespace
    var pluginName = 'clndr';

    // This is the default calendar template. This can be overridden.
    var clndrTemplate =
        "<div class='clndr-controls'>" +
            "<div class='clndr-control-button'>" +
                "<span class='clndr-previous-button'>previous</span>" +
            "</div>" +
            "<div class='month'><%= month %> <%= year %></div>" +
            "<div class='clndr-control-button rightalign'>" +
                "<span class='clndr-next-button'>next</span>" +
            "</div>" +
        "</div>" +
        "<table class='clndr-table' border='0' cellspacing='0' cellpadding='0'>" +
            "<thead>" +
                "<tr class='header-days'>" +
                "<% for(var i = 0; i < daysOfTheWeek.length; i++) { %>" +
                    "<td class='header-day'><%= daysOfTheWeek[i] %></td>" +
                "<% } %>" +
                "</tr>" +
            "</thead>" +
            "<tbody>" +
            "<% for(var i = 0; i < numberOfRows; i++){ %>" +
                "<tr>" +
                "<% for(var j = 0; j < 7; j++){ %>" +
                "<% var d = j + i * 7; %>" +
                    "<td class='<%= days[d].classes %>'>" +
                        "<div class='day-contents'><%= days[d].day %></div>" +
                    "</td>" +
                "<% } %>" +
                "</tr>" +
            "<% } %>" +
            "</tbody>" +
        "</table>";

    // Defaults used throughout the application, see docs.
    var defaults = {
        events: [],
        ready: null,
        extras: null,
        render: null,
        moment: null,
        weekOffset: 0,
        constraints: null,
        forceSixRows: null,
        selectedDate: null,
        doneRendering: null,
        daysOfTheWeek: null,
        multiDayEvents: null,
        startWithMonth: null,
        dateParameter: 'date',
        template: clndrTemplate,
        showAdjacentMonths: true,
        trackSelectedDate: false,
        adjacentDaysChangeMonth: false,
        ignoreInactiveDaysInSelection: null,
        lengthOfTime: {
            days: null,
            interval: 1,
            months: null
        },
        clickEvents: {
            click: null,
            today: null,
            nextYear: null,
            nextMonth: null,
            nextInterval: null,
            previousYear: null,
            onYearChange: null,
            previousMonth: null,
            onMonthChange: null,
            previousInterval: null,
            onIntervalChange: null
        },
        targets: {
            day: 'day',
            empty: 'empty',
            nextButton: 'clndr-next-button',
            todayButton: 'clndr-today-button',
            previousButton: 'clndr-previous-button',
            nextYearButton: 'clndr-next-year-button',
            previousYearButton: 'clndr-previous-year-button'
        },
        classes: {
            past: "past",
            today: "today",
            event: "event",
            inactive: "inactive",
            selected: "selected",
            lastMonth: "last-month",
            nextMonth: "next-month",
            adjacentMonth: "adjacent-month"
        },
    };

    /**
     * The actual plugin constructor.
     * Parses the events and lengthOfTime options to build a calendar of day
     * objects containing event information from the events array.
     */
    function Clndr(element, options) {
        var dayDiff;
        var constraintEnd;
        var constraintStart;

        this.element = element;

        // Merge the default options with user-provided options
        this.options = $.extend(true, {}, defaults, options);

        // Check if moment was passed in as a dependency
        if (this.options.moment) {
            moment = this.options.moment;
        }

        // Boolean values used to log if any contraints are met
        this.constraints = {
            next: true,
            today: true,
            previous: true,
            nextYear: true,
            previousYear: true
        };

        // If there are events, we should run them through our
        // addMomentObjectToEvents function which will add a date object that
        // we can use to make life easier. This is only necessarywhen events
        // are provided on instantiation, since our setEvents function uses
        // addMomentObjectToEvents.
        if (this.options.events.length) {
            if (this.options.multiDayEvents) {
                this.options.events =
                    this.addMultiDayMomentObjectsToEvents(this.options.events);
            } else {
                this.options.events =
                    this.addMomentObjectToEvents(this.options.events);
            }
        }

        // This used to be a place where we'd figure out the current month,
        // but since we want to open up support for arbitrary lengths of time
        // we're going to store the current range in addition to the current
        // month.
        if (this.options.lengthOfTime.months || this.options.lengthOfTime.days) {
            // We want to establish intervalStart and intervalEnd, which will
            // keep track of our boundaries. Let's look at the possibilities...
            if (this.options.lengthOfTime.months) {
                // Gonna go right ahead and annihilate any chance for bugs here
                this.options.lengthOfTime.days = null;

                // The length is specified in months. Is there a start date?
                if (this.options.lengthOfTime.startDate) {
                    this.intervalStart =
                        moment(this.options.lengthOfTime.startDate)
                            .startOf('month');
                } else if (this.options.startWithMonth) {
                    this.intervalStart =
                        moment(this.options.startWithMonth)
                            .startOf('month');
                } else {
                    this.intervalStart = moment().startOf('month');
                }

                // Subtract a day so that we are at the end of the interval. We
                // always want intervalEnd to be inclusive.
                this.intervalEnd = moment(this.intervalStart)
                    .add(this.options.lengthOfTime.months, 'months')
                    .subtract(1, 'days');
                this.month = this.intervalStart.clone();
            }
            else if (this.options.lengthOfTime.days) {
                // The length is specified in days. Start date?
                if (this.options.lengthOfTime.startDate) {
                    this.intervalStart =
                        moment(this.options.lengthOfTime.startDate)
                            .startOf('day');
                } else {
                    this.intervalStart = moment().weekday(0).startOf('day');
                }

                this.intervalEnd = moment(this.intervalStart)
                    .add(this.options.lengthOfTime.days - 1, 'days')
                    .endOf('day');
                this.month = this.intervalStart.clone();
            }
        // No length of time specified so we're going to default into using the
        // current month as the time period.
        } else {
            this.month = moment().startOf('month');
            this.intervalStart = moment(this.month);
            this.intervalEnd = moment(this.month).endOf('month');
        }

        if (this.options.startWithMonth) {
            this.month = moment(this.options.startWithMonth).startOf('month');
            this.intervalStart = moment(this.month);
            this.intervalEnd = (this.options.lengthOfTime.days)
                ? moment(this.month)
                    .add(this.options.lengthOfTime.days - 1, 'days')
                    .endOf('day')
                : moment(this.month).endOf('month');
        }

        // If we've got constraints set, make sure the interval is within them.
        if (this.options.constraints) {
            // First check if the startDate exists & is later than now.
            if (this.options.constraints.startDate) {
                constraintStart = moment(this.options.constraints.startDate);

                // We need to handle the constraints differently for weekly
                // calendars vs. monthly calendars.
                if (this.options.lengthOfTime.days) {
                    if (this.intervalStart.isBefore(constraintStart, 'week')) {
                        this.intervalStart = constraintStart.startOf('week');
                    }

                    // If the new interval period is less than the desired length
                    // of time, or before the starting interval, then correct it.
                    dayDiff = this.intervalStart.diff(this.intervalEnd, 'days');

                    if (dayDiff < this.options.lengthOfTime.days
                        || this.intervalEnd.isBefore(this.intervalStart))
                    {
                        this.intervalEnd = moment(this.intervalStart)
                            .add(this.options.lengthOfTime.days - 1, 'days')
                            .endOf('day');
                        this.month = this.intervalStart.clone();
                    }
                }
                else {
                    if (this.intervalStart.isBefore(constraintStart, 'month')) {
                        // Try to preserve the date by moving only the month.
                        this.intervalStart
                            .set('month', constraintStart.month())
                            .set('year', constraintStart.year());
                        this.month
                            .set('month', constraintStart.month())
                            .set('year', constraintStart.year());
                    }

                    // Check if the ending interval is earlier than now.
                    if (this.intervalEnd.isBefore(constraintStart, 'month')) {
                        this.intervalEnd
                            .set('month', constraintStart.month())
                            .set('year', constraintStart.year());
                    }
                }
            }

            // Make sure the intervalEnd is before the endDate.
            if (this.options.constraints.endDate) {
                constraintEnd = moment(this.options.constraints.endDate);

                // We need to handle the constraints differently for weekly
                // calendars vs. monthly calendars.
                if (this.options.lengthOfTime.days) {
                    // The starting interval is after our ending constraint.
                    if (this.intervalStart.isAfter(constraintEnd, 'week')) {
                        this.intervalStart = moment(constraintEnd)
                            .endOf('week')
                            .subtract(this.options.lengthOfTime.days - 1, 'days')
                            .startOf('day');
                        this.intervalEnd = moment(constraintEnd)
                            .endOf('week');
                        this.month = this.intervalStart.clone();
                    }
                }
                else {
                    if (this.intervalEnd.isAfter(constraintEnd, 'month')) {
                        this.intervalEnd
                            .set('month', constraintEnd.month())
                            .set('year', constraintEnd.year());
                        this.month
                            .set('month', constraintEnd.month())
                            .set('year', constraintEnd.year());
                    }

                    // Check if the starting interval is later than the ending.
                    if (this.intervalStart.isAfter(constraintEnd, 'month')) {
                        this.intervalStart
                            .set('month', constraintEnd.month())
                            .set('year', constraintEnd.year());
                    }
                }
            }
        }

        this._defaults = defaults;
        this._name = pluginName;

        // Some first-time initialization -> day of the week offset, template
        // compiling, making and storing some elements we'll need later, and
        // event handling for the controller.
        this.init();
    }

    /**
     * Calendar initialization.
     * Sets up the days of the week, the rendering function, binds all of the
     * events to the rendered calendar, and then stores the node locally.
     */
    Clndr.prototype.init = function () {
        // Create the days of the week using moment's current language setting
        this.daysOfTheWeek = this.options.daysOfTheWeek || [];

        if (!this.options.daysOfTheWeek) {
            this.daysOfTheWeek = [];

            for (var i = 0; i < 7; i++) {
                this.daysOfTheWeek.push(
                    moment().weekday(i).format('dd').charAt(0));
            }
        }

        // Shuffle the week if there's an offset
        if (this.options.weekOffset) {
            this.daysOfTheWeek = this.shiftWeekdayLabels(this.options.weekOffset);
        }

        // Quick and dirty test to make sure rendering is possible.
        if (!$.isFunction(this.options.render)) {
            this.options.render = null;

            if (typeof _ === 'undefined') {
                throw new Error(
                    "Underscore was not found. Please include underscore.js " +
                    "OR provide a custom render function.");
            } else {
                // We're just going ahead and using underscore here if no
                // render method has been supplied.
                this.compiledClndrTemplate = _.template(this.options.template);
            }
        }

        // Create the parent element that will hold the plugin and save it
        // for later
        $(this.element).html("<div class='clndr'></div>");
        this.calendarContainer = $('.clndr', this.element);

        // Attach event handlers for clicks on buttons/cells
        this.bindEvents();

        // Do a normal render of the calendar template
        this.render();

        // If a ready callback has been provided, call it.
        if (this.options.ready) {
            this.options.ready.apply(this, []);
        }
    };

    Clndr.prototype.shiftWeekdayLabels = function (offset) {
        var days = this.daysOfTheWeek;

        for (var i = 0; i < offset; i++) {
            days.push(days.shift());
        }

        return days;
    };

    /**
     * This is where the magic happens. Given a starting date and ending date,
     * an array of calendarDay objects is constructed that contains appropriate
     * events and classes depending on the circumstance.
     */
    Clndr.prototype.createDaysObject = function (startDate, endDate) {
        // This array will hold numbers for the entire grid (even the blank
        // spaces).
        var daysArray = [],
            date = startDate.clone(),
            lengthOfInterval = endDate.diff(startDate, 'days'),
            startOfLastMonth, endOfLastMonth, startOfNextMonth,
            endOfNextMonth, diff, dateIterator;

        // This is a helper object so that days can resolve their classes
        // correctly. Don't use it for anything please.
        this._currentIntervalStart = startDate.clone();

        // Filter the events list (if it exists) to events that are happening
        // last month, this month and next month (within the current grid view).
        this.eventsLastMonth = [];
        this.eventsNextMonth = [];
        this.eventsThisInterval = [];

        // Event parsing
        if (this.options.events.length) {
            // Here are the only two cases where we don't get an event in our
            // interval:
            //   startDate | endDate | e.start   | e.end
            //   e.start   | e.end   | startDate | endDate
            this.eventsThisInterval = $(this.options.events).filter(
                function () {
                    var afterEnd = this._clndrStartDateObject.isAfter(endDate),
                        beforeStart = this._clndrEndDateObject.isBefore(startDate);

                    if (beforeStart || afterEnd) {
                        return false;
                    } else {
                        return true;
                    }
                }).toArray();

            if (this.options.showAdjacentMonths) {
                startOfLastMonth = startDate.clone()
                    .subtract(1, 'months')
                    .startOf('month');
                endOfLastMonth = startOfLastMonth.clone().endOf('month');
                startOfNextMonth = endDate.clone()
                    .add(1, 'months')
                    .startOf('month');
                endOfNextMonth = startOfNextMonth.clone().endOf('month');

                this.eventsLastMonth = $(this.options.events).filter(
                    function () {
                        var beforeStart = this._clndrEndDateObject
                            .isBefore(startOfLastMonth);
                        var afterEnd = this._clndrStartDateObject
                            .isAfter(endOfLastMonth);

                        if (beforeStart || afterEnd) {
                            return false;
                        } else {
                            return true;
                        }
                    }).toArray();

                this.eventsNextMonth = $(this.options.events).filter(
                    function () {
                        var beforeStart = this._clndrEndDateObject
                            .isBefore(startOfNextMonth);
                        var afterEnd = this._clndrStartDateObject
                            .isAfter(endOfNextMonth);

                        if (beforeStart || afterEnd) {
                            return false;
                        } else {
                            return true;
                        }
                    }).toArray();
            }
        }

        // If diff is greater than 0, we'll have to fill in last days of the
        // previous month to account for the empty boxes in the grid. We also
        // need to take into account the weekOffset parameter. None of this
        // needs to happen if the interval is being specified in days rather
        // than months.
        if (!this.options.lengthOfTime.days) {
            diff = date.weekday() - this.options.weekOffset;

            if (diff < 0) {
                diff += 7;
            }

            if (this.options.showAdjacentMonths) {
                for (var i = 1; i <= diff; i++) {
                    var day = moment([
                        startDate.year(),
                        startDate.month(),
                        i
                    ]).subtract(diff, 'days');
                    daysArray.push(
                        this.createDayObject(
                            day,
                            this.eventsLastMonth
                        ));
                }
            } else {
                for (var i = 0; i < diff; i++) {
                    daysArray.push(
                        this.calendarDay({
                            classes: this.options.targets.empty +
                                " " + this.options.classes.lastMonth
                        }));
                }
            }
        }

        // Now we push all of the days in the interval
        dateIterator = startDate.clone();

        while (dateIterator.isBefore(endDate) || dateIterator.isSame(endDate, 'day')) {
            daysArray.push(
                this.createDayObject(
                    dateIterator.clone(),
                    this.eventsThisInterval
                ));
            dateIterator.add(1, 'days');
        }

        // ...and if there are any trailing blank boxes, fill those in with the
        // next month first days. Again, we can ignore this if the interval is
        // specified in days.
        if (!this.options.lengthOfTime.days) {
            while (daysArray.length % 7 !== 0) {
                if (this.options.showAdjacentMonths) {
                    daysArray.push(
                        this.createDayObject(
                            dateIterator.clone(),
                            this.eventsNextMonth
                        ));
                } else {
                    daysArray.push(
                        this.calendarDay({
                            classes: this.options.targets.empty + " " +
                                this.options.classes.nextMonth
                        }));
                }
                dateIterator.add(1, 'days');
            }
        }

        // If we want to force six rows of calendar, now's our Last Chance to
        // add another row. If the 42 seems explicit it's because we're
        // creating a 7-row grid and 6 rows of 7 is always 42!
        if (this.options.forceSixRows && daysArray.length !== 42) {
            while (daysArray.length < 42) {
                if (this.options.showAdjacentMonths) {
                    daysArray.push(
                        this.createDayObject(
                            dateIterator.clone(),
                            this.eventsNextMonth
                        ));
                    dateIterator.add(1, 'days');
                } else {
                    daysArray.push(
                        this.calendarDay({
                            classes: this.options.targets.empty + " " +
                                this.options.classes.nextMonth
                    }));
                }
            }
        }

        return daysArray;
    };

    Clndr.prototype.createDayObject = function (day, monthEvents) {
        var j = 0,
            self = this,
            now = moment(),
            eventsToday = [],
            extraClasses = "",
            properties = {
                isToday: false,
                isInactive: false,
                isAdjacentMonth: false
            },
            startMoment, endMoment, selectedMoment;

        // Validate moment date
        if (!day.isValid() && day.hasOwnProperty('_d') && day._d != undefined) {
            day = moment(day._d);
        }

        for (j; j < monthEvents.length; j++) {
            // Keep in mind that the events here already passed the month/year
            // test. Now all we have to compare is the moment.date(), which
            // returns the day of the month.
            var start = monthEvents[j]._clndrStartDateObject,
                end = monthEvents[j]._clndrEndDateObject;
            // If today is the same day as start or is after the start, and
            // if today is the same day as the end or before the end ...
            // woohoo semantics!
            if ( (day.isSame(start, 'day') || day.isAfter(start, 'day'))
                && (day.isSame(end, 'day') || day.isBefore(end, 'day')) )
            {
                eventsToday.push( monthEvents[j] );
            }
        }

        if (now.format("YYYY-MM-DD") == day.format("YYYY-MM-DD")) {
            extraClasses += (" " + this.options.classes.today);
            properties.isToday = true;
        }

        if (day.isBefore(now, 'day')) {
            extraClasses += (" " + this.options.classes.past);
        }

        if (eventsToday.length) {
            extraClasses += (" " + this.options.classes.event);
        }

        if (!this.options.lengthOfTime.days) {
            if (this._currentIntervalStart.month() > day.month()) {
                extraClasses += (" " + this.options.classes.adjacentMonth);
                properties.isAdjacentMonth = true;

                this._currentIntervalStart.year() === day.year()
                    ? extraClasses += (" " + this.options.classes.lastMonth)
                    : extraClasses += (" " + this.options.classes.nextMonth);
            }
            else if (this._currentIntervalStart.month() < day.month()) {
                extraClasses += (" " + this.options.classes.adjacentMonth);
                properties.isAdjacentMonth = true;

                this._currentIntervalStart.year() === day.year()
                    ? extraClasses += (" " + this.options.classes.nextMonth)
                    : extraClasses += (" " + this.options.classes.lastMonth);
            }
        }

        // If there are constraints, we need to add the inactive class to the
        // days outside of them
        if (this.options.constraints) {
            endMoment = moment(this.options.constraints.endDate);
            startMoment = moment(this.options.constraints.startDate);

            if (this.options.constraints.startDate && day.isBefore(startMoment)) {
                extraClasses += (" " + this.options.classes.inactive);
                properties.isInactive = true;
            }

            if (this.options.constraints.endDate && day.isAfter(endMoment)) {
                extraClasses += (" " + this.options.classes.inactive);
                properties.isInactive = true;
            }
        }

        // Validate moment date
        if (!day.isValid() && day.hasOwnProperty('_d') && day._d != undefined) {
            day = moment(day._d);
        }

        // Check whether the day is "selected"
        selectedMoment = moment(this.options.selectedDate);

        if (this.options.selectedDate && day.isSame(selectedMoment, 'day')) {
            extraClasses += (" " + this.options.classes.selected);
        }

        // We're moving away from using IDs in favor of classes, since when
        // using multiple calendars on a page we are technically violating the
        // uniqueness of IDs.
        extraClasses += " calendar-day-" + day.format("YYYY-MM-DD");
        // Day of week
        extraClasses += " calendar-dow-" + day.weekday();

        return this.calendarDay({
            date: day,
            day: day.date(),
            events: eventsToday,
            properties: properties,
            classes: this.options.targets.day + extraClasses
        });
    };

    Clndr.prototype.render = function () {
        // Get rid of the previous set of calendar parts. This should handle garbage
        // collection according to jQuery's docs:
        //   http://api.jquery.com/empty/
        //   To avoid memory leaks, jQuery removes other constructs such as
        //   data and event handlers from the child elements before removing
        //   the elements themselves.
        var data = {},
            end = null,
            start = null,
            oneYearFromEnd = this.intervalEnd.clone().add(1, 'years'),
            oneYearAgo = this.intervalStart.clone().subtract(1, 'years'),
            days, months, currentMonth, eventsThisInterval,
            numberOfRows;
        this.calendarContainer.empty();

        if (this.options.lengthOfTime.days) {
            days = this.createDaysObject(
                this.intervalStart.clone(),
                this.intervalEnd.clone());
            data = {
                days: days,
                months: [],
                year: null,
                month: null,
                eventsLastMonth: [],
                eventsNextMonth: [],
                eventsThisMonth: [],
                extras: this.options.extras,
                daysOfTheWeek: this.daysOfTheWeek,
                intervalEnd: this.intervalEnd.clone(),
                numberOfRows: Math.ceil(days.length / 7),
                intervalStart: this.intervalStart.clone(),
                eventsThisInterval: this.eventsThisInterval
            };
        }
        else if (this.options.lengthOfTime.months) {
            months = [];
            numberOfRows = 0;
            eventsThisInterval = [];

            for (i = 0; i < this.options.lengthOfTime.months; i++) {
                var currentIntervalStart = this.intervalStart
                    .clone()
                    .add(i, 'months');
                var currentIntervalEnd = currentIntervalStart
                    .clone()
                    .endOf('month');
                var days = this.createDaysObject(
                    currentIntervalStart,
                    currentIntervalEnd);
                // Save events processed for each month into a master array of
                // events for this interval
                eventsThisInterval.push(this.eventsThisInterval);
                months.push({
                    days: days,
                    month: currentIntervalStart
                });
            }

            // Get the total number of rows across all months
            for (i = 0; i < months.length; i++) {
                numberOfRows += Math.ceil(months[i].days.length / 7);
            }

            data = {
                days: [],
                year: null,
                month: null,
                months: months,
                eventsThisMonth: [],
                numberOfRows: numberOfRows,
                extras: this.options.extras,
                intervalEnd: this.intervalEnd,
                intervalStart: this.intervalStart,
                daysOfTheWeek: this.daysOfTheWeek,
                eventsLastMonth: this.eventsLastMonth,
                eventsNextMonth: this.eventsNextMonth,
                eventsThisInterval: eventsThisInterval,
            };
        }
        else {
            // Get an array of days and blank spaces
            days = this.createDaysObject(
                this.month.clone().startOf('month'),
                this.month.clone().endOf('month'));
            // This is to prevent a scope/naming issue between this.month and
            // data.month
            currentMonth = this.month;

            data = {
                days: days,
                months: [],
                intervalEnd: null,
                intervalStart: null,
                year: this.month.year(),
                eventsThisInterval: null,
                extras: this.options.extras,
                month: this.month.format('MMMM'),
                daysOfTheWeek: this.daysOfTheWeek,
                eventsLastMonth: this.eventsLastMonth,
                eventsNextMonth: this.eventsNextMonth,
                numberOfRows: Math.ceil(days.length / 7),
                eventsThisMonth: this.eventsThisInterval
            };
        }

        // Render the calendar with the data above & bind events to its
        // elements
        if ( !this.options.render) {
            this.calendarContainer.html(
                this.compiledClndrTemplate(data));
        } else {
            this.calendarContainer.html(
                this.options.render.apply(this, [data]));
        }

        // If there are constraints, we need to add the 'inactive' class to
        // the controls.
        if (this.options.constraints) {
            // In the interest of clarity we're just going to remove all
            // inactive classes and re-apply them each render.
            for (var target in this.options.targets) {
                if (target != this.options.targets.day) {
                    this.element.find('.' + this.options.targets[target])
                        .toggleClass(
                            this.options.classes.inactive,
                            false);
                }
            }

            // Just like the classes we'll set this internal state to true and
            // handle the disabling below.
            for (var i in this.constraints) {
                this.constraints[i] = true;
            }

            if (this.options.constraints.startDate) {
                start = moment(this.options.constraints.startDate);
            }

            if (this.options.constraints.endDate) {
                end = moment(this.options.constraints.endDate);
            }

            // Deal with the month controls first. Do we have room to go back?
            if (start
                && (start.isAfter(this.intervalStart)
                    || start.isSame(this.intervalStart, 'day')))
            {
                this.element.find('.' + this.options.targets.previousButton)
                    .toggleClass(this.options.classes.inactive, true);
                this.constraints.previous = !this.constraints.previous;
            }

            // Do we have room to go forward?
            if (end
                && (end.isBefore(this.intervalEnd)
                    || end.isSame(this.intervalEnd, 'day')))
            {
                this.element.find('.' + this.options.targets.nextButton)
                    .toggleClass(this.options.classes.inactive, true);
                this.constraints.next = !this.constraints.next;
            }

            // What's last year looking like?
            if (start && start.isAfter(oneYearAgo)) {
                this.element.find('.' + this.options.targets.previousYearButton)
                    .toggleClass(this.options.classes.inactive, true);
                this.constraints.previousYear = !this.constraints.previousYear;
            }

            // How about next year?
            if (end && end.isBefore(oneYearFromEnd)) {
                this.element.find('.' + this.options.targets.nextYearButton)
                    .toggleClass(this.options.classes.inactive, true);
                this.constraints.nextYear = !this.constraints.nextYear;
            }

            // Today? We could put this in init(), but we want to support the
            // user changing the constraints on a living instance.
            if ( (start && start.isAfter( moment(), 'month' ))
                || (end && end.isBefore( moment(), 'month' )) )
            {
                this.element.find('.' + this.options.targets.today)
                    .toggleClass(this.options.classes.inactive, true);
                this.constraints.today = !this.constraints.today;
            }
        }

        if (this.options.doneRendering) {
            this.options.doneRendering.apply(this, []);
        }
    };

    Clndr.prototype.bindEvents = function () {
        var data = {},
            self = this,
            $container = $(this.element),
            targets = this.options.targets,
            classes = self.options.classes,
            eventType = (this.options.useTouchEvents === true)
                ? 'touchstart'
                : 'click',
            eventName = eventType + '.clndr';

        // Make sure we don't already have events
        $container
            .off(eventName, '.' + targets.day)
            .off(eventName, '.' + targets.empty)
            .off(eventName, '.' + targets.nextButton)
            .off(eventName, '.' + targets.todayButton)
            .off(eventName, '.' + targets.previousButton)
            .off(eventName, '.' + targets.nextYearButton)
            .off(eventName, '.' + targets.previousYearButton);

        // Target the day elements and give them click events
        $container.on(eventName, '.' + targets.day, function (event) {
            var target,
                $currentTarget = $(event.currentTarget);

            if (self.options.clickEvents.click) {
                target = self.buildTargetObject(event.currentTarget, true);
                self.options.clickEvents.click.apply(self, [target]);
            }

            // If adjacentDaysChangeMonth is on, we need to change the
            // month here.
            if (self.options.adjacentDaysChangeMonth) {
                if ($currentTarget.is('.' + classes.lastMonth)) {
                    self.backActionWithContext(self);
                }
                else if ($currentTarget.is('.' + classes.nextMonth)) {
                    self.forwardActionWithContext(self);
                }
            }

            // if trackSelectedDate is on, we need to handle click on a new day
            if (self.options.trackSelectedDate) {
                if (self.options.ignoreInactiveDaysInSelection
                    && $currentTarget.hasClass(classes.inactive))
                {
                    return;
                }

                // Remember new selected date
                self.options.selectedDate =
                    self.getTargetDateString(event.currentTarget);
                // Handle "selected" class. This handles more complex templates
                // that may have the selected elements nested.
                $container.find('.' + classes.selected)
                    .removeClass(classes.selected);
                $currentTarget.addClass(classes.selected);
            }
        });

        // Target the empty calendar boxes as well
        $container.on(eventName, '.' + targets.empty, function (event) {
            var target,
                $eventTarget = $(event.currentTarget);

            if (self.options.clickEvents.click) {
                target = self.buildTargetObject(event.currentTarget, false);
                self.options.clickEvents.click.apply(self, [target]);
            }

            if (self.options.adjacentDaysChangeMonth) {
                if ($eventTarget.is('.' + classes.lastMonth)) {
                    self.backActionWithContext(self);
                }
                else if ($eventTarget.is('.' + classes.nextMonth)) {
                    self.forwardActionWithContext(self);
                }
            }
        });

        // Bind the previous, next and today buttons. We pass the current
        // context along with the event so that it can update this instance.
        data = {
            context: this
        };

        $container
            .on(eventName, '.' + targets.todayButton, data, this.todayAction)
            .on(eventName, '.' + targets.nextButton, data, this.forwardAction)
            .on(eventName, '.' + targets.previousButton, data, this.backAction)
            .on(eventName, '.' + targets.nextYearButton, data, this.nextYearAction)
            .on(eventName, '.' + targets.previousYearButton, data, this.previousYearAction);
    };

    /**
     * If the user provided a click callback we'd like to give them something
     * nice to work with. buildTargetObject takes the DOM element that was
     * clicked and returns an object with the DOM element, events, and the date
     * (if the latter two exist). Currently it is based on the id, however it'd
     * be nice to use a data- attribute in the future.
     */
    Clndr.prototype.buildTargetObject = function (currentTarget, targetWasDay) {
        // This is our default target object, assuming we hit an empty day
        // with no events.
        var target = {
            date: null,
            events: [],
            element: currentTarget
        };
        var dateString, filterFn;

        // Did we click on a day or just an empty box?
        if (targetWasDay) {
            dateString = this.getTargetDateString(currentTarget);
            target.date = (dateString)
                ? moment(dateString)
                : null;

            // Do we have events?
            if (this.options.events) {
                // Are any of the events happening today?
                if (this.options.multiDayEvents) {
                    filterFn = function () {
                        var isSameStart = target.date.isSame(
                            this._clndrStartDateObject,
                            'day');
                        var isAfterStart = target.date.isAfter(
                            this._clndrStartDateObject,
                            'day');
                        var isSameEnd = target.date.isSame(
                            this._clndrEndDateObject,
                            'day');
                        var isBeforeEnd = target.date.isBefore(
                            this._clndrEndDateObject,
                            'day');
                        return (isSameStart || isAfterStart)
                            && (isSameEnd || isBeforeEnd);
                    };
                }
                else {
                    filterFn = function () {
                        var startString = this._clndrStartDateObject
                            .format('YYYY-MM-DD');
                        return startString == dateString;
                    };
                }

                // Filter the dates down to the ones that match.
                target.events = $.makeArray(
                    $(this.options.events).filter(filterFn));
            }
        }

        return target;
    };

    /**
     * Get moment date object of the date associated with the given target.
     * This method is meant to be called on ".day" elements.
     */
    Clndr.prototype.getTargetDateString = function (target) {
        // Our identifier is in the list of classNames. Find it!
        var classNameIndex = target.className.indexOf('calendar-day-');

        if (classNameIndex !== -1) {
            // Our unique identifier is always 23 characters long.
            // If this feels a little wonky, that's probably because it is.
            // Open to suggestions on how to improve this guy.
            return target.className.substring(
                classNameIndex + 13,
                classNameIndex + 23);
        }

        return null;
    };

    /**
     * Triggers any applicable events given a change in the calendar's start
     * and end dates. ctx contains the current (changed) start and end date,
     * orig contains the original start and end dates.
     */
    Clndr.prototype.triggerEvents = function (ctx, orig) {
        var timeOpt = ctx.options.lengthOfTime,
            eventsOpt = ctx.options.clickEvents,
            newInt = {
                end: ctx.intervalEnd,
                start: ctx.intervalStart
            },
            intervalArg = [
                moment(ctx.intervalStart),
                moment(ctx.intervalEnd)
            ],
            monthArg = [moment(ctx.month)],
            nextYear, prevYear, yearChanged,
            nextMonth, prevMonth, monthChanged,
            nextInterval, prevInterval, intervalChanged;

        // We want to determine if any of the change conditions have been
        // hit and then trigger our events based off that.
        nextMonth = newInt.start.isAfter( orig.start )
            && (Math.abs(newInt.start.month() - orig.start.month()) == 1
                || orig.start.month() === 11 && newInt.start.month() === 0);
        prevMonth = newInt.start.isBefore( orig.start )
            && (Math.abs(orig.start.month() - newInt.start.month()) == 1
                || orig.start.month() === 0 && newInt.start.month() === 11);
        monthChanged = newInt.start.month() !== orig.start.month()
            || newInt.start.year() !== orig.start.year();
        nextYear = newInt.start.year() - orig.start.year() === 1
            || newInt.end.year() - orig.end.year() === 1;
        prevYear = orig.start.year() - newInt.start.year() === 1
            || orig.end.year() - newInt.end.year() === 1;
        yearChanged = newInt.start.year() !== orig.start.year();

        // Only configs with a time period will get the interval change event
        if (timeOpt.days || timeOpt.months) {
            nextInterval = newInt.start.isAfter(orig.start);
            prevInterval = newInt.start.isBefore(orig.start);
            intervalChanged = nextInterval || prevInterval;

            if (nextInterval && eventsOpt.nextInterval) {
                eventsOpt.nextInterval.apply(ctx, intervalArg);
            }

            if (prevInterval && eventsOpt.previousInterval) {
                eventsOpt.previousInterval.apply(ctx, intervalArg);
            }

            if (intervalChanged && eventsOpt.onIntervalChange) {
                eventsOpt.onIntervalChange.apply(ctx, intervalArg);
            }
        }
        // @V2-todo see https://github.com/kylestetz/CLNDR/issues/225
        else {
            if (nextMonth && eventsOpt.nextMonth) {
                eventsOpt.nextMonth.apply(ctx, monthArg);
            }

            if (prevMonth && eventsOpt.previousMonth) {
                eventsOpt.previousMonth.apply(ctx, monthArg);
            }

            if (monthChanged && eventsOpt.onMonthChange) {
                eventsOpt.onMonthChange.apply(ctx, monthArg);
            }

            if (nextYear && eventsOpt.nextYear) {
                eventsOpt.nextYear.apply(ctx, monthArg);
            }

            if (prevYear && eventsOpt.previousYear) {
                eventsOpt.previousYear.apply(ctx, monthArg);
            }

            if (yearChanged && eventsOpt.onYearChange) {
                eventsOpt.onYearChange.apply(ctx, monthArg);
            }
        }
    };

    /**
     * Main action to go backward one period. Other methods call these, like
     * backAction which proxies jQuery events, and backActionWithContext which
     * is an internal method that this library uses.
     */
    Clndr.prototype.back = function (options /*, ctx */) {
        var yearChanged = null,
            ctx = (arguments.length > 1)
                ? arguments[ 1 ]
                : this,
            timeOpt = ctx.options.lengthOfTime,
            defaults = {
                withCallbacks: false
            },
            orig = {
                end: ctx.intervalEnd.clone(),
                start: ctx.intervalStart.clone()
            };

        // Extend any options
        options = $.extend(true, {}, defaults, options);

        // Before we do anything, check if any constraints are limiting this
        if (!ctx.constraints.previous) {
            return ctx;
        }

        if (!timeOpt.days) {
            // Shift the interval by a month (or several months)
            ctx.intervalStart
                .subtract(timeOpt.interval, 'months')
                .startOf('month');
            ctx.intervalEnd = ctx.intervalStart.clone()
                .add(timeOpt.months || timeOpt.interval, 'months')
                .subtract(1, 'days')
                .endOf('month');
            ctx.month = ctx.intervalStart.clone();
        }
        else {
            // Shift the interval in days
            ctx.intervalStart
                .subtract(timeOpt.interval, 'days')
                .startOf('day');
            ctx.intervalEnd = ctx.intervalStart.clone()
                .add(timeOpt.days - 1, 'days')
                .endOf('day');
            // @V2-todo Useless, but consistent with API
            ctx.month = ctx.intervalStart.clone();
        }

        ctx.render();

        if (options.withCallbacks) {
            ctx.triggerEvents(ctx, orig);
        }

        return ctx;
    };

    Clndr.prototype.backAction = function (event) {
        var ctx = event.data.context;
        ctx.backActionWithContext(ctx);
    };

    Clndr.prototype.backActionWithContext = function (ctx) {
        ctx.back({
            withCallbacks: true
        }, ctx);
    };

    Clndr.prototype.previous = function (options) {
        // Alias
        return this.back(options);
    };

    /**
     * Main action to go forward one period. Other methods call these, like
     * forwardAction which proxies jQuery events, and backActionWithContext
     * which is an internal method that this library uses.
     */
    Clndr.prototype.forward = function (options /*, ctx */) {
        var ctx = (arguments.length > 1)
                ? arguments[1]
                : this,
            timeOpt = ctx.options.lengthOfTime,
            defaults = {
                withCallbacks: false
            },
            orig = {
                end: ctx.intervalEnd.clone(),
                start: ctx.intervalStart.clone()
            };

        // Extend any options
        options = $.extend(true, {}, defaults, options);

        // Before we do anything, check if any constraints are limiting this
        if (!ctx.constraints.next) {
            return ctx;
        }

        if (ctx.options.lengthOfTime.days) {
            // Shift the interval in days
            ctx.intervalStart
                .add(timeOpt.interval, 'days')
                .startOf('day');
            ctx.intervalEnd = ctx.intervalStart.clone()
                .add(timeOpt.days - 1, 'days')
                .endOf('day');
            // @V2-todo Useless, but consistent with API
            ctx.month = ctx.intervalStart.clone();
        }
        else {
            // Shift the interval by a month (or several months)
            ctx.intervalStart
                .add(timeOpt.interval, 'months')
                .startOf('month');
            ctx.intervalEnd = ctx.intervalStart.clone()
                .add(timeOpt.months || timeOpt.interval, 'months')
                .subtract(1, 'days')
                .endOf('month');
            ctx.month = ctx.intervalStart.clone();
        }

        ctx.render();

        if (options.withCallbacks) {
            ctx.triggerEvents(ctx, orig);
        }

        return ctx;
    };

    Clndr.prototype.forwardAction = function (event) {
        var ctx = event.data.context;
        ctx.forwardActionWithContext(ctx);
    };

    Clndr.prototype.forwardActionWithContext = function (ctx) {
        ctx.forward({
            withCallbacks: true
        }, ctx);
    };

    Clndr.prototype.next = function (options) {
        // Alias
        return this.forward(options);
    };

    /**
     * Main action to go back one year.
     */
    Clndr.prototype.previousYear = function (options /*, ctx */) {
        var ctx = (arguments.length > 1)
                ? arguments[1]
                : this,
            defaults = {
                withCallbacks: false
            },
            orig = {
                end: ctx.intervalEnd.clone(),
                start: ctx.intervalStart.clone()
            };

        // Extend any options
        options = $.extend(true, {}, defaults, options);

        // Before we do anything, check if any constraints are limiting this
        if (!ctx.constraints.previousYear) {
            return ctx;
        }

        ctx.month.subtract(1, 'year');
        ctx.intervalStart.subtract(1, 'year');
        ctx.intervalEnd.subtract(1, 'year');
        ctx.render();

        if (options.withCallbacks) {
            ctx.triggerEvents(ctx, orig);
        }

        return ctx;
    };

    Clndr.prototype.previousYearAction = function (event) {
        var ctx = event.data.context;
        ctx.previousYear({
            withCallbacks: true
        }, ctx);
    };

    /**
     * Main action to go forward one year.
     */
    Clndr.prototype.nextYear = function (options /*, ctx */) {
        var ctx = (arguments.length > 1)
                ? arguments[1]
                : this,
            defaults = {
                withCallbacks: false
            },
            orig = {
                end: ctx.intervalEnd.clone(),
                start: ctx.intervalStart.clone()
            };

        // Extend any options
        options = $.extend(true, {}, defaults, options);

        // Before we do anything, check if any constraints are limiting this
        if (!ctx.constraints.nextYear) {
            return ctx;
        }

        ctx.month.add(1, 'year');
        ctx.intervalStart.add(1, 'year');
        ctx.intervalEnd.add(1, 'year');
        ctx.render();

        if (options.withCallbacks) {
            ctx.triggerEvents(ctx, orig);
        }

        return ctx;
    };

    Clndr.prototype.nextYearAction = function (event) {
        var ctx = event.data.context;
        ctx.nextYear({
            withCallbacks: true
        }, ctx);
    };

    Clndr.prototype.today = function (options /*, ctx */) {
        var ctx = (arguments.length > 1)
                ? arguments[1]
                : this,
            timeOpt = ctx.options.lengthOfTime,
            defaults = {
                withCallbacks: false
            },
            orig = {
                end: ctx.intervalEnd.clone(),
                start: ctx.intervalStart.clone()
            };

        // Extend any options
        options = $.extend(true, {}, defaults, options);
        // @V2-todo Only used for legacy month view
        ctx.month = moment().startOf('month');

        if (timeOpt.days) {
            // If there was a startDate specified, we should figure out what
            // the weekday is and use that as the starting point of our
            // interval. If not, go to today.weekday(0).
            if (timeOpt.startDate) {
                ctx.intervalStart = moment()
                    .weekday(timeOpt.startDate.weekday())
                    .startOf('day');
            } else {
                ctx.intervalStart = moment().weekday(0).startOf('day');
            }

            ctx.intervalEnd = ctx.intervalStart.clone()
                .add(timeOpt.days - 1, 'days')
                .endOf('day');
        }
        else {
            // Set the intervalStart to this month.
            ctx.intervalStart = moment().startOf('month');
            ctx.intervalEnd = ctx.intervalStart.clone()
                .add(timeOpt.months || timeOpt.interval, 'months')
                .subtract(1, 'days')
                .endOf('month');
        }

        // No need to re-render if we didn't change months.
        if (!ctx.intervalStart.isSame(orig.start)
            || !ctx.intervalEnd.isSame(orig.end))
        {
            ctx.render();
        }

        // Fire the today event handler regardless of any change
        if (options.withCallbacks) {
            if (ctx.options.clickEvents.today) {
                ctx.options.clickEvents.today.apply(ctx, [moment(ctx.month)]);
            }

            ctx.triggerEvents(ctx, orig);
        }
    };

    Clndr.prototype.todayAction = function (event) {
        var ctx = event.data.context;
        ctx.today({
            withCallbacks: true
        }, ctx);
    };

    /**
     * Changes the month. Accepts 0-11 or a full/partial month name e.g. "Jan",
     * "February", "Mar", etc.
     */
    Clndr.prototype.setMonth = function (newMonth, options) {
        var timeOpt = this.options.lengthOfTime,
            orig = {
                end: this.intervalEnd.clone(),
                start: this.intervalStart.clone()
            };

        if (timeOpt.days || timeOpt.months) {
            console.log(
                'You are using a custom date interval. Use ' +
                'Clndr.setIntervalStart(startDate) instead.');
            return this;
        }

        this.month.month(newMonth);
        this.intervalStart = this.month.clone().startOf('month');
        this.intervalEnd = this.intervalStart.clone().endOf('month');
        this.render();

        if (options && options.withCallbacks) {
            this.triggerEvents(this, orig);
        }

        return this;
    };

    Clndr.prototype.setYear = function (newYear, options) {
        var orig = {
            end: this.intervalEnd.clone(),
            start: this.intervalStart.clone()
        };

        this.month.year(newYear);
        this.intervalEnd.year(newYear);
        this.intervalStart.year(newYear);
        this.render();

        if (options && options.withCallbacks) {
            this.triggerEvents(this, orig);
        }

        return this;
    };

    /**
     * Sets the start of the time period according to newDate. newDate can be
     * a string or a moment object.
     */
    Clndr.prototype.setIntervalStart = function (newDate, options) {
        var timeOpt = this.options.lengthOfTime,
            orig = {
                end: this.intervalEnd.clone(),
                start: this.intervalStart.clone()
            };

        if (!timeOpt.days && !timeOpt.months) {
            console.log(
                'You are using a custom date interval. Use ' +
                'Clndr.setIntervalStart(startDate) instead.');
            return this;
        }

        if (timeOpt.days) {
            this.intervalStart = moment(newDate).startOf('day');
            this.intervalEnd = this.intervalStart.clone()
                .add(timeOpt - 1, 'days')
                .endOf('day');
        } else {
            this.intervalStart = moment(newDate).startOf('month');
            this.intervalEnd = this.intervalStart.clone()
                .add(timeOpt.months || timeOpt.interval, 'months')
                .subtract(1, 'days')
                .endOf('month');
        }

        this.month = this.intervalStart.clone();
        this.render();

        if (options && options.withCallbacks) {
            this.triggerEvents(this, orig);
        }

        return this;
    };

    /**
     * Overwrites extras in the calendar and triggers a render.
     */
    Clndr.prototype.setExtras = function (extras) {
        this.options.extras = extras;
        this.render();
        return this;
    };

    /**
     * Overwrites events in the calendar and triggers a render.
     */
    Clndr.prototype.setEvents = function (events) {
        // Go through each event and add a moment object
        if (this.options.multiDayEvents) {
            this.options.events = this.addMultiDayMomentObjectsToEvents(events);
        } else {
            this.options.events = this.addMomentObjectToEvents(events);
        }

        this.render();
        return this;
    };

    /**
     * Adds additional events to the calendar and triggers a render.
     */
    Clndr.prototype.addEvents = function (events /*, reRender*/) {
        var reRender = (arguments.length > 1)
            ? arguments[1]
            : true;

        // Go through each event and add a moment object
        if (this.options.multiDayEvents) {
            this.options.events = $.merge(
                this.options.events,
                this.addMultiDayMomentObjectsToEvents(events));
        } else {
            this.options.events = $.merge(
                this.options.events,
                this.addMomentObjectToEvents(events));
        }

        if (reRender) {
            this.render();
        }

        return this;
    };

    /**
     * Passes all events through a matching function. Any that pass a truth
     * test will be removed from the calendar's events. This triggers a render.
     */
    Clndr.prototype.removeEvents = function (matchingFn) {
        for (var i = this.options.events.length - 1; i >= 0; i--) {
            if (matchingFn(this.options.events[i]) == true) {
                this.options.events.splice(i, 1);
            }
        }

        this.render();
        return this;
    };

    Clndr.prototype.addMomentObjectToEvents = function (events) {
        var i = 0,
            self = this;

        for (i; i < events.length; i++) {
            // Add the date as both start and end, since it's a single-day
            // event by default
            events[i]._clndrStartDateObject =
                moment(events[i][self.options.dateParameter]);
            events[i]._clndrEndDateObject =
                moment(events[i][self.options.dateParameter]);
        }

        return events;
    };

    Clndr.prototype.addMultiDayMomentObjectsToEvents = function (events) {
        var i = 0,
            self = this,
            multiEvents = self.options.multiDayEvents;

        for (i; i < events.length; i++) {
            var end = events[i][multiEvents.endDate],
                start = events[i][multiEvents.startDate];
            // If we don't find the startDate OR endDate fields, look for
            // singleDay
            if (!end && !start) {
                events[i]._clndrEndDateObject =
                    moment(events[i][multiEvents.singleDay]);
                events[i]._clndrStartDateObject =
                    moment(events[i][multiEvents.singleDay]);
            }
            // Otherwise use startDate and endDate, or whichever one is present
            else {
                events[i]._clndrEndDateObject = moment(end || start);
                events[i]._clndrStartDateObject = moment(start || end);
            }
        }

        return events;
    };

    Clndr.prototype.calendarDay = function (options) {
        var defaults = {
            day: "",
            date: null,
            events: [],
            classes: this.options.targets.empty
        };

        return $.extend({}, defaults, options);
    };

    Clndr.prototype.destroy = function () {
        var $container = $(this.calendarContainer);
        $container.parent().data('plugin_clndr', null);
        this.options = defaults;
        $container.empty().remove();
        this.element = null;
    };

    $.fn.clndr = function (options) {
        var clndrInstance;

        if (this.length > 1) {
            throw new Error(
                "CLNDR does not support multiple elements yet. Make sure " +
                "your clndr selector returns only one element.");
        }

        if (!this.length) {
            throw new Error(
                "CLNDR cannot be instantiated on an empty selector.");
        }

        if (!this.data('plugin_clndr')) {
            clndrInstance = new Clndr(this, options);
            this.data('plugin_clndr', clndrInstance);
            return clndrInstance;
        }

        return this.data('plugin_clndr');
    };
}));
--
-- PostgreSQL database dump
--

-- Dumped from database version 9.0.3
-- Dumped by pg_dump version 9.0.3
-- Started on 2014-08-26 11:58:44

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

--
-- TOC entry 341 (class 2612 OID 11574)
-- Name: plpgsql; Type: PROCEDURAL LANGUAGE; Schema: -; Owner: postgres
--

CREATE OR REPLACE PROCEDURAL LANGUAGE plpgsql;


ALTER PROCEDURAL LANGUAGE plpgsql OWNER TO postgres;

SET search_path = public, pg_catalog;

--
-- TOC entry 24 (class 1255 OID 108621)
-- Dependencies: 5 341
-- Name: up_actualizarcampo(integer, integer, character varying, character varying, character varying, integer, text, integer, integer, integer, text, text, integer, text, character varying, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION up_actualizarcampo(vidcampo integer, vidtabla integer, vnombre character varying, vdescripcion character varying, vayuda character varying, vlongitud integer, vcss text, vidtipocontrol integer, vidtipodato integer, vidcombo integer, vvalores text, vdefecto text, vidhandler integer, vscript_js text, vllave character varying, vvalor_opcional text) RETURNS void
    LANGUAGE plpgsql
    AS $$
    BEGIN

	UPDATE campo
	SET nombre=vnombre, descripcion=vdescripcion, ayuda=vayuda, longitud=vlongitud, 
       css=vcss, idtipocontrol=vidtipocontrol, idtipodato=vidtipodato, 
       idcombo=vidcombo, valores=vvalores, defecto=vdefecto, idhandler=vidhandler, script_js=vscript_js, llave=vllave, 
       valor_opcional=vvalor_opcional
	WHERE idcampo=vidcampo and idtabla=vidtabla;

END;
  $$;


ALTER FUNCTION public.up_actualizarcampo(vidcampo integer, vidtabla integer, vnombre character varying, vdescripcion character varying, vayuda character varying, vlongitud integer, vcss text, vidtipocontrol integer, vidtipodato integer, vidcombo integer, vvalores text, vdefecto text, vidhandler integer, vscript_js text, vllave character varying, vvalor_opcional text) OWNER TO postgres;

--
-- TOC entry 25 (class 1255 OID 108604)
-- Dependencies: 5 341
-- Name: up_actualizaroperacion(integer, integer, character varying, character varying, character varying, text, text, character, character, integer, text, text, text, text, text, text, text, text, text, integer, character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION up_actualizaroperacion(vidoperacion integer, vidtabla integer, vnombre character varying, vdescripcion character varying, vayuda character varying, vcss text, vicono text, vmultiple character, vtipo character, vidhandler integer, vaccion text, vargumentos text, vscript_js_pref text, vscript_js text, vscript_js_suf text, vscript_php text, vfunction_sql text, vargumentos_sql text, vscript_sql text, vidvista_redirect integer, vcontenedor_redirect character varying) RETURNS void
    LANGUAGE plpgsql
    AS $$
    BEGIN

	UPDATE operacion
	SET nombre=vnombre, descripcion=vdescripcion, ayuda=vayuda, css=vcss, 
       icono=vicono, multiple=vmultiple, tipo=vtipo, idhandler=vidhandler, accion=vaccion, argumentos=vargumentos, 
       script_js_pref=vscript_js_pref, script_js=vscript_js, script_js_suf=vscript_js_suf, script_php=vscript_php, 
       function_sql=vfunction_sql, argumentos_sql=vargumentos_sql, script_sql=vscript_sql, idvista_redirect=vidvista_redirect, 
       contenedor_redirect=vcontenedor_redirect
	WHERE idoperacion=vidoperacion and idtabla=vidtabla;

END;
  $$;


ALTER FUNCTION public.up_actualizaroperacion(vidoperacion integer, vidtabla integer, vnombre character varying, vdescripcion character varying, vayuda character varying, vcss text, vicono text, vmultiple character, vtipo character, vidhandler integer, vaccion text, vargumentos text, vscript_js_pref text, vscript_js text, vscript_js_suf text, vscript_php text, vfunction_sql text, vargumentos_sql text, vscript_sql text, vidvista_redirect integer, vcontenedor_redirect character varying) OWNER TO postgres;

--
-- TOC entry 20 (class 1255 OID 108591)
-- Dependencies: 5 341
-- Name: up_actualizartabla(integer, character varying, character varying, character varying, character); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION up_actualizartabla(vidtabla integer, vnombre character varying, vdescripcion character varying, vayuda character varying, vtipotabla character) RETURNS void
    LANGUAGE plpgsql
    AS $$
    BEGIN

	UPDATE tabla
	SET nombre=vnombre, descripcion=vdescripcion, ayuda=vayuda, tipotabla=vtipotabla
	WHERE idtabla=vidtabla;

END;
  $$;


ALTER FUNCTION public.up_actualizartabla(vidtabla integer, vnombre character varying, vdescripcion character varying, vayuda character varying, vtipotabla character) OWNER TO postgres;

--
-- TOC entry 19 (class 1255 OID 108519)
-- Dependencies: 341 5
-- Name: up_agregarcampo(integer, character varying, character varying, character varying, integer, character varying, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION up_agregarcampo(vidtabla integer, vnombre character varying, vdescripcion character varying, vayuda character varying, vlongitud integer, vcss character varying, vidtipocontrol integer) RETURNS void
    LANGUAGE plpgsql
    AS $$
	DECLARE vIdCampo integer;
    BEGIN

	SELECT (case when MAX(idcampo) is null then 0 else MAX(idcampo) end) + 1 into vIdCampo FROM campo WHERE idtabla=vidtabla;
	
	INSERT INTO campo(
            idcampo, idtabla, nombre, descripcion, ayuda, longitud, css, 
            idtipocontrol)
    VALUES (vIdCampo, vidtabla, vnombre, vdescripcion, vayuda, vlongitud, vcss, 
            vidtipocontrol);            

END;
  $$;


ALTER FUNCTION public.up_agregarcampo(vidtabla integer, vnombre character varying, vdescripcion character varying, vayuda character varying, vlongitud integer, vcss character varying, vidtipocontrol integer) OWNER TO postgres;

--
-- TOC entry 23 (class 1255 OID 108603)
-- Dependencies: 5 341
-- Name: up_agregaroperacion(integer, character varying, character varying, character varying, text, text, character, character, integer, text, text, text, text, text, text, text, text, text, integer, character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION up_agregaroperacion(vidtabla integer, vnombre character varying, vdescripcion character varying, vayuda character varying, vcss text, vicono text, vmultiple character, vtipo character, vidhandler integer, vaccion text, vargumentos text, vscript_js_pref text, vscript_js text, vscript_js_suf text, vscript_php text, vfunction_sql text, vargumentos_sql text, vscript_sql text, vidvista_redirect integer, vcontenedor_redirect character varying) RETURNS void
    LANGUAGE plpgsql
    AS $$
	DECLARE vIdOperacion integer;
    BEGIN

	SELECT (case when MAX(idoperacion) is null then 0 else MAX(idoperacion) end) + 1 into vIdOperacion FROM operacion WHERE idtabla=vidtabla;
	
        INSERT INTO operacion(
            idoperacion, idtabla, nombre, descripcion, ayuda, css, icono, 
            multiple, tipo, idhandler, accion, argumentos, script_js_pref, 
            script_js, script_js_suf, script_php, function_sql, argumentos_sql, 
            script_sql, idvista_redirect, contenedor_redirect)
	VALUES (vIdOperacion, vidtabla, vnombre, vdescripcion, vayuda, vcss, vicono, vmultiple, vtipo, vidhandler, vaccion, vargumentos, vscript_js_pref, vscript_js, vscript_js_suf, vscript_php, vfunction_sql, vargumentos_sql, vscript_sql, vidvista_redirect, vcontenedor_redirect);
         

END;
  $$;


ALTER FUNCTION public.up_agregaroperacion(vidtabla integer, vnombre character varying, vdescripcion character varying, vayuda character varying, vcss text, vicono text, vmultiple character, vtipo character, vidhandler integer, vaccion text, vargumentos text, vscript_js_pref text, vscript_js text, vscript_js_suf text, vscript_php text, vfunction_sql text, vargumentos_sql text, vscript_sql text, vidvista_redirect integer, vcontenedor_redirect character varying) OWNER TO postgres;

--
-- TOC entry 18 (class 1255 OID 108518)
-- Dependencies: 5 341
-- Name: up_agregartabla(character varying, character varying, character varying, character); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION up_agregartabla(vnombre character varying, vdescripcion character varying, vayuda character varying, vtipotabla character) RETURNS void
    LANGUAGE plpgsql
    AS $$
	DECLARE vIdTabla integer;
    BEGIN

	SELECT (case when MAX(idtabla) is null then 0 else MAX(idtabla) end) + 1 into vIdTabla FROM tabla;

	INSERT INTO tabla(
        idtabla, nombre, descripcion, ayuda, tipotabla)
	VALUES (vIdTabla, vnombre, vdescripcion, vayuda, vtipotabla);

END;
  $$;


ALTER FUNCTION public.up_agregartabla(vnombre character varying, vdescripcion character varying, vayuda character varying, vtipotabla character) OWNER TO postgres;

--
-- TOC entry 22 (class 1255 OID 108602)
-- Dependencies: 5 341
-- Name: up_agregarvista(character varying, character varying, character varying, text, integer, integer, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION up_agregarvista(vnombre character varying, vdescripcion character varying, vtitulo character varying, vcss text, vidtipovista integer, vidformato integer, vidtabla integer) RETURNS void
    LANGUAGE plpgsql
    AS $$
	DECLARE vIdVista integer;
    BEGIN

	SELECT (case when MAX(idvista) is null then 0 else MAX(idvista) end) + 1 into vIdVista FROM vista;

	INSERT INTO vista(
            idvista, nombre, descripcion, titulo, css, idtipovista, idformato, idtabla)
	VALUES (vIdVista, vnombre, vdescripcion, vtitulo, vcss, vidtipovista, vidformato, vidtabla);

END;
  $$;


ALTER FUNCTION public.up_agregarvista(vnombre character varying, vdescripcion character varying, vtitulo character varying, vcss text, vidtipovista integer, vidformato integer, vidtabla integer) OWNER TO postgres;

--
-- TOC entry 26 (class 1255 OID 108605)
-- Dependencies: 5 341
-- Name: up_agregarvista_atributo(integer, character varying, character varying, character varying, integer, character, integer, integer, text, integer, text, character, character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION up_agregarvista_atributo(vidvista integer, vnombre character varying, vetiqueta character varying, vayuda character varying, vidtabla integer, vtipoatributo character, vidatributo integer, vidregionvista integer, vdefecto text, vorden integer, vcss text, vvisible character, voperador character varying) RETURNS void
    LANGUAGE plpgsql
    AS $$
	DECLARE vIdVista_Atributo integer;
    BEGIN

	SELECT (case when MAX(idvista_atributo) is null then 0 else MAX(idvista_atributo) end) + 1 into vIdVista_Atributo FROM vista_atributo WHERE idvista=vidvista;

	INSERT INTO vista_atributo(
            idvista_atributo, idvista, nombre, etiqueta, ayuda, idtabla, 
            tipoatributo, idatributo, idregionvista, defecto, orden, css, 
            visible, operador)
	VALUES (vIdVista_Atributo, vidvista, vnombre, vetiqueta, vayuda, vidtabla, 
            vtipoatributo, vidatributo, vidregionvista, vdefecto, vorden, vcss,
            vvisible, voperador);
         

END;
  $$;


ALTER FUNCTION public.up_agregarvista_atributo(vidvista integer, vnombre character varying, vetiqueta character varying, vayuda character varying, vidtabla integer, vtipoatributo character, vidatributo integer, vidregionvista integer, vdefecto text, vorden integer, vcss text, vvisible character, voperador character varying) OWNER TO postgres;

--
-- TOC entry 21 (class 1255 OID 108592)
-- Dependencies: 341 5
-- Name: up_eliminartabla(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION up_eliminartabla(vidtabla integer) RETURNS void
    LANGUAGE plpgsql
    AS $$
    BEGIN

	UPDATE tabla
	SET estado='A'
	WHERE idtabla=vidtabla;

END;
  $$;


ALTER FUNCTION public.up_eliminartabla(vidtabla integer) OWNER TO postgres;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 1541 (class 1259 OID 108626)
-- Dependencies: 5
-- Name: atributo_control; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE atributo_control (
    idatributo_control integer NOT NULL,
    idtipocontrol integer NOT NULL,
    nombre character varying(50),
    descripcion character varying(100),
    valores text,
    defecto text
);


ALTER TABLE public.atributo_control OWNER TO postgres;

--
-- TOC entry 1534 (class 1259 OID 108492)
-- Dependencies: 1821 1822 5
-- Name: campo; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE campo (
    idcampo integer NOT NULL,
    idtabla integer NOT NULL,
    nombre character varying(50),
    descripcion character varying(100),
    ayuda character varying(100),
    longitud integer,
    css text,
    idtipocontrol integer,
    estado character(1) DEFAULT 'N'::bpchar,
    idtipodato integer DEFAULT 1,
    idcombo integer,
    valores text,
    defecto text,
    idhandler integer,
    script_js text,
    llave character varying(10),
    valor_opcional text,
    campos_ref text
);


ALTER TABLE public.campo OWNER TO postgres;

--
-- TOC entry 1864 (class 0 OID 0)
-- Dependencies: 1534
-- Name: TABLE campo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE campo IS 'Almacena los campos para cada tabla';


--
-- TOC entry 1865 (class 0 OID 0)
-- Dependencies: 1534
-- Name: COLUMN campo.estado; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN campo.estado IS 'N->Normal, A->Anulado';


--
-- TOC entry 1866 (class 0 OID 0)
-- Dependencies: 1534
-- Name: COLUMN campo.valores; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN campo.valores IS 'Cuando se trata de un combo vista o tabla, este campo sirve para indicar los campos a mostrar';


--
-- TOC entry 1867 (class 0 OID 0)
-- Dependencies: 1534
-- Name: COLUMN campo.llave; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN campo.llave IS 'PK->Llave Primaria Autoincremental, PKD->Llave Primaria Dependiente,
N->Ninguna';


--
-- TOC entry 1868 (class 0 OID 0)
-- Dependencies: 1534
-- Name: COLUMN campo.campos_ref; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN campo.campos_ref IS 'Permite indicar el o los campo de referencia, es decir los campo de los cual depende para su contrucción';


--
-- TOC entry 1542 (class 1259 OID 108634)
-- Dependencies: 5
-- Name: campo_atributo; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE campo_atributo (
    idcampo_atributo integer NOT NULL,
    idtabla integer NOT NULL,
    idcampo integer NOT NULL,
    idatributo_control integer NOT NULL,
    idtipocontrol integer NOT NULL,
    valor text
);


ALTER TABLE public.campo_atributo OWNER TO postgres;

--
-- TOC entry 1539 (class 1259 OID 108576)
-- Dependencies: 5
-- Name: handler; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE handler (
    idhandler integer NOT NULL,
    nombre character varying(50),
    descripcion character varying(100)
);


ALTER TABLE public.handler OWNER TO postgres;

--
-- TOC entry 1538 (class 1259 OID 108559)
-- Dependencies: 1826 1827 5
-- Name: operacion; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE operacion (
    idoperacion integer NOT NULL,
    idtabla integer NOT NULL,
    nombre character varying(50),
    descripcion character varying(100),
    ayuda character varying(100),
    css text,
    icono text,
    multiple character(1) DEFAULT 'N'::bpchar,
    tipo character(1) DEFAULT 'C'::bpchar,
    idhandler integer,
    accion text,
    argumentos text,
    script_js_pref text,
    script_js text,
    script_js_suf text,
    script_php text,
    function_sql text,
    argumentos_sql text,
    script_sql text,
    idvista_redirect integer,
    contenedor_redirect character varying(50)
);


ALTER TABLE public.operacion OWNER TO postgres;

--
-- TOC entry 1869 (class 0 OID 0)
-- Dependencies: 1538
-- Name: COLUMN operacion.tipo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN operacion.tipo IS 'E->Encabezado, C->Campo';


--
-- TOC entry 1533 (class 1259 OID 108486)
-- Dependencies: 1820 5
-- Name: tabla; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE tabla (
    idtabla integer NOT NULL,
    nombre character varying(50),
    descripcion character varying(100),
    ayuda character varying(100),
    tipotabla character(1),
    estado character(1) DEFAULT 'N'::bpchar
);


ALTER TABLE public.tabla OWNER TO postgres;

--
-- TOC entry 1870 (class 0 OID 0)
-- Dependencies: 1533
-- Name: TABLE tabla; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE tabla IS 'Almacena todas las tablas';


--
-- TOC entry 1871 (class 0 OID 0)
-- Dependencies: 1533
-- Name: COLUMN tabla.tipotabla; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN tabla.tipotabla IS 'B->Base de datos, V->Virtual';


--
-- TOC entry 1872 (class 0 OID 0)
-- Dependencies: 1533
-- Name: COLUMN tabla.estado; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN tabla.estado IS 'A->Anulado, N->Normal';


--
-- TOC entry 1535 (class 1259 OID 108512)
-- Dependencies: 1823 5
-- Name: tipocontrol; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE tipocontrol (
    idtipocontrol integer NOT NULL,
    nombre character varying(50),
    descripcion character varying(100),
    global character(1) DEFAULT 'N'::bpchar
);


ALTER TABLE public.tipocontrol OWNER TO postgres;

--
-- TOC entry 1873 (class 0 OID 0)
-- Dependencies: 1535
-- Name: TABLE tipocontrol; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE tipocontrol IS 'Almacena los tipos de control';


--
-- TOC entry 1874 (class 0 OID 0)
-- Dependencies: 1535
-- Name: COLUMN tipocontrol.global; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN tipocontrol.global IS 'S->Si, N->No';


--
-- TOC entry 1540 (class 1259 OID 108608)
-- Dependencies: 1828 5
-- Name: tipodato; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE tipodato (
    idtipodato integer NOT NULL,
    nombre character varying(50),
    descripcion character varying(100),
    "precision" character(1) DEFAULT 'N'::bpchar
);


ALTER TABLE public.tipodato OWNER TO postgres;

--
-- TOC entry 1875 (class 0 OID 0)
-- Dependencies: 1540
-- Name: COLUMN tipodato."precision"; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN tipodato."precision" IS 'S->Si o N->No';


--
-- TOC entry 1536 (class 1259 OID 108521)
-- Dependencies: 5
-- Name: vista; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE vista (
    idvista integer NOT NULL,
    nombre character varying(50),
    descripcion character varying(100),
    titulo character varying(100),
    css text,
    idtipovista integer,
    idformato integer,
    idtabla integer
);


ALTER TABLE public.vista OWNER TO postgres;

--
-- TOC entry 1876 (class 0 OID 0)
-- Dependencies: 1536
-- Name: COLUMN vista.idtipovista; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN vista.idtipovista IS 'Grilla, form, etc';


--
-- TOC entry 1877 (class 0 OID 0)
-- Dependencies: 1536
-- Name: COLUMN vista.idformato; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN vista.idformato IS 'lista html, tabla, etc';


--
-- TOC entry 1537 (class 1259 OID 108529)
-- Dependencies: 1824 1825 5
-- Name: vista_atributo; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE vista_atributo (
    idvista_atributo integer NOT NULL,
    idvista integer NOT NULL,
    nombre character varying(50),
    etiqueta character varying(100),
    ayuda character varying,
    idtabla integer,
    tipoatributo character(1),
    idatributo integer,
    idregionvista integer,
    defecto text,
    orden integer,
    css text,
    visible character(1) DEFAULT 'S'::bpchar,
    operador character varying DEFAULT '='::character varying
);


ALTER TABLE public.vista_atributo OWNER TO postgres;

--
-- TOC entry 1878 (class 0 OID 0)
-- Dependencies: 1537
-- Name: COLUMN vista_atributo.tipoatributo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN vista_atributo.tipoatributo IS 'C->Campo, O->Operacion';


--
-- TOC entry 1879 (class 0 OID 0)
-- Dependencies: 1537
-- Name: COLUMN vista_atributo.idatributo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN vista_atributo.idatributo IS 'idcampo o idoperacion, dependiento del tipoatributo';


--
-- TOC entry 1880 (class 0 OID 0)
-- Dependencies: 1537
-- Name: COLUMN vista_atributo.defecto; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN vista_atributo.defecto IS 'valor por defecto';


--
-- TOC entry 1881 (class 0 OID 0)
-- Dependencies: 1537
-- Name: COLUMN vista_atributo.orden; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN vista_atributo.orden IS 'numero de orden';


--
-- TOC entry 1882 (class 0 OID 0)
-- Dependencies: 1537
-- Name: COLUMN vista_atributo.visible; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN vista_atributo.visible IS 'S->Si, N->No';


--
-- TOC entry 1857 (class 0 OID 108626)
-- Dependencies: 1541
-- Data for Name: atributo_control; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY atributo_control (idatributo_control, idtipocontrol, nombre, descripcion, valores, defecto) FROM stdin;
1	3	rows	rows	\N	5
2	3	cols	cols	\N	25
\.


--
-- TOC entry 1850 (class 0 OID 108492)
-- Dependencies: 1534
-- Data for Name: campo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY campo (idcampo, idtabla, nombre, descripcion, ayuda, longitud, css, idtipocontrol, estado, idtipodato, idcombo, valores, defecto, idhandler, script_js, llave, valor_opcional, campos_ref) FROM stdin;
2	1	nombre	nombre	nombre	50		1	N	1	\N	\N	\N	\N	\N	\N	\N	\N
3	1	descripcion	descripcion	descripcion	100		1	N	1	\N	\N	\N	\N	\N	\N	\N	\N
4	1	ayuda	ayuda	ayuda	100		1	N	1	\N	\N	\N	\N	\N	\N	\N	\N
3	2	nombre	nombre	nombre	50		1	N	1	\N	\N	\N	\N	\N	\N	\N	\N
4	2	descripcion	descripcion	descripcion	100		1	N	1	\N	\N	\N	\N	\N	\N	\N	\N
2	4	nombre	nombre		0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
3	4	descripcion	descripcion		0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
4	4	titulo	titulo		0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
5	4	idtipovista	idtipovista		0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
6	4	idtabla	idtabla		0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
7	4	idformato	idformato		0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
8	4	css	css		0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
5	2	ayuda	ayuda		0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
3	5	nombre	nombre		0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
4	5	etiqueta	etiqueta		0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
5	5	ayuda	ayuda		0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
10	5	operador	operador		0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
11	5	defecto	defecto		0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
13	5	css	css		0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
9	5	idregionvista	idregionvista		0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
3	3	nombre	nombre		0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
4	3	descripcion	descripcion		0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
5	3	ayuda	ayuda		0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
6	3	css	css		0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
7	3	icono	icono		0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
11	3	accion	accion		0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
12	3	argumentos	argumentos		0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
13	3	script_js_pref	script_js_pref		0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
14	3	script_js	script_js		0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
15	3	script_js_suf	script_js_suf		0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
16	3	script_php	script_php		0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
17	3	function_sql	function_sql		0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
18	3	argumentos_sql	argumentos_sql		0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
19	3	script_sql	script_sql		0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
21	3	contenedor_redirect	contenedor_redirect		0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
5	1	tipotabla	tipotabla	tipotabla	1		4	N	1	\N	B|Base de datos\r\nV|Virtual	B	\N	\N	\N	\N	\N
13	2	defecto	defecto	defecto	0		1	N	1	\N	\N	\N	\N	\N	\N	\N	\N
14	5	visible	visible		0		4	N	1	0	S|Si\r\nN|No	S	0			\N	\N
7	5	tipoatributo	tipoatributo		0		4	N	1	0	C|Campo\r\nO|Operación		0			\N	\N
2	7	nombre	nombre	nombre	0		1	N	1	\N	\N	\N	\N	\N	\N	\N	\N
8	3	multiple	multiple		0		4	N	1	0	S|Si\r\nN|No	S	0			\N	\N
3	7	descripcion	descripcion	descripcion	0		1	N	1	\N	\N	\N	\N	\N	\N	\N	\N
9	2	idtipodato	idtipodato		0		5	N	2	9	idtipodato,nombre	1	0			\N	\N
2	8	nombre	nombre	nombre	0		1	N	1	\N	\N	\N	\N	\N	\N	\N	\N
12	2	valores	valores	valores	0		3	N	1	0			0			\N	\N
7	2	css	css	css	0		0	N	1	\N	\N	\N	\N	\N	\N	\N	\N
3	8	descripcion	descripcion	descripcion	0		1	N	1	\N	\N	\N	\N	\N	\N	\N	\N
4	8	global	global	global	0		1	N	1	\N	\N	\N	\N	\N	\N	\N	\N
9	3	tipo	tipo		0		4	N	1	0	E|Encabezado\r\nC|Campo\r\nM|Multiple	C	0			\N	\N
1	1	idtabla	idtabla	idtabla	0		2	N	2	0			0		PK		\N
17	2	valor_opcional	valor_opcional	valor_opcional	0		1	N	1	\N	\N	\N	\N	\N	\N	\N	\N
2	3	idtabla	idtabla		0		1	N	2	0			0		PKD		\N
20	3	idvista_redirect	idvista_redirect		0		6	N	1	5	idvista,nombre	0	0			0|Ninguna	\N
16	2	llave	llave	llave	0		4	N	1	0	PK|Llave Primaria (autoincremental)\r\nPKD|Llave Primaria dependiente (no autoincremental)\r\n|Ninguna	N	0				\N
1	5	idvista_atributo	idvista_atributo		0		1	N	2	0			0		PK		\N
2	2	idtabla	idtabla	idtabla	0		1	N	1	0			0		PKD		\N
14	2	idhandler	idhandler	idhandler	0		5	N	1	7	idhandler,nombre		0			0|Ninguno	\N
11	2	idcombo	idcombo	idcombo	0		1	N	2	0			0				\N
1	2	idcampo	idcampo	idcampo	0		2	N	2	0			0		PK		\N
6	2	longitud	longitud	longitud	0		1	N	2	0			0				\N
1	3	idoperacion	idoperacion		0		2	N	2	0			0		PK		\N
10	3	idhandler	idhandler		0		5	N	1	7	idhandler,nombre		0			0|Ninguno	\N
1	4	idvista	idvista		0		2	N	2	0			0		PK		\N
2	5	idvista	idvista		0		1	N	2	0			0		PKD		\N
1	7	idhandler	idhandler	idhandler	0		1	N	2	0			0		PK		\N
1	8	idtipocontrol	idtipocontrol	idtipocontrol	0		1	N	2	0			0		PK		\N
6	5	idtabla	idtabla		0		5	N	2	1	idtabla,nombre	1	0				\N
15	2	script_js	script_js	script_js	0		3	N	1	0			0				\N
12	5	orden	orden		0		1	N	2	0			0				\N
8	2	idtipocontrol	idtipocontrol	idtipocontrol	0		5	N	2	8	idtipocontrol,nombre	1	0	\N			\N
8	5	idatributo	idatributo		0		5	N	2	2	idcampo,nombre		0	function exec_idatributo(){\r\n\tif(document.getElementById('tipoatributo').value=='C'){\r\n\t\tsetAjax(9,'ajaxGeneral','COMBO','idtablacontrol=5&idcampocontrol=8&nombre=idatributo&etiqueta=idatributo&css=&idtipocontrol=5&idcombo=2&valor_opcional=&valores=idcampo,nombre&defecto=&datocampo=&campos_ref=idtabla&ajax=SI&idtabla='+document.getElementById('idtabla').value,'li_idatributo');\r\n\t}else{\r\n\t\tsetAjax(9,'ajaxGeneral','COMBO','idtablacontrol=5&idcampocontrol=8&nombre=idatributo&etiqueta=idatributo&css=&idtipocontrol=5&idcombo=3&valor_opcional=&valores=idoperacion,nombre&defecto=&datocampo=&campos_ref=idtabla&ajax=SI&idtabla='+document.getElementById('idtabla').value,'li_idatributo');\r\n\t}\r\n}\r\n$('#tipoatributo').click(function(evt) {\r\n\texec_idatributo();\r\n});\r\nexec_idatributo();			idtabla
\.


--
-- TOC entry 1858 (class 0 OID 108634)
-- Dependencies: 1542
-- Data for Name: campo_atributo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY campo_atributo (idcampo_atributo, idtabla, idcampo, idatributo_control, idtipocontrol, valor) FROM stdin;
\.


--
-- TOC entry 1855 (class 0 OID 108576)
-- Dependencies: 1539
-- Data for Name: handler; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY handler (idhandler, nombre, descripcion) FROM stdin;
2	Actualizar	Actualizar
1	Nuevo	Nuevo
4	Redireccionar	Redireccionar
3	Eliminar	Eliminar
5	Otro	Otro
\.


--
-- TOC entry 1854 (class 0 OID 108559)
-- Dependencies: 1538
-- Data for Name: operacion; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY operacion (idoperacion, idtabla, nombre, descripcion, ayuda, css, icono, multiple, tipo, idhandler, accion, argumentos, script_js_pref, script_js, script_js_suf, script_php, function_sql, argumentos_sql, script_sql, idvista_redirect, contenedor_redirect) FROM stdin;
1	1	Nuevo	Nuevo	Nuevo registro	\N	\N	N	E	1	nuevo	\N	\N	\N	\N	\N	up_agregartabla	nombre,descripcion,ayuda,tipotabla	\N	1	frm_mant
1	2	Nuevo	Nuevo	Nuevo registro	\N	\N	N	E	1	nuevo	\N	\N	\N	\N	\N	up_agregarcampo	idtabla,nombre,descripcion,ayuda,longitud,css,idtipocontrol	\N	3	frm_mant
1	3	nuevo	nuevo	nuevo			N	E	1	nuevo						up_agregaroperacion	idtabla,nombre,descripcion,ayuda,css,icono,multiple,tipo,idhandler,accion,argumentos,script_js_pref,script_js,script_js_suf,script_php,function_sql,argumentos_sql,script_sql,idvista_redirect,contenedor_redirect		7	frm_mant
3	1	Eliminar	Eliminar	Eliminar	\N	\N	N	C	3	eliminar	idtabla	\N	\N	\N	\N	up_eliminartabla	idtabla	\N	\N	\N
4	1	Campos	Campos	Campos	\N	\N	N	C	4	listGeneral	idtabla	\N	\N	\N	\N	\N	\N	\N	3	content
5	1	Operaciones	Operaciones	Operaciones	\N	\N	N	C	4	listGeneral	idtabla	\N	\N	\N	\N	\N	\N	\N	7	content
2	3	actualizar	actualizar	actualizar			N	C	2	actualizar	idoperacion,idtabla					up_actualizaroperacion	idoperacion,idtabla,nombre,descripcion,ayuda,css,icono,multiple,tipo,idhandler,accion,argumentos,script_js_pref,script_js,script_js_suf,script_php,function_sql,argumentos_sql,script_sql,idvista_redirect,contenedor_redirect		7	frm_mant
1	4	Nuevo	Nuevo	Nuevo registro	\N	\N	N	E	1	nuevo	\N	\N	\N	\N	\N	up_agregarvista	nombre,descripcion,titulo,css,idtipovista,idformato,idtabla	\N	5	frm_mant
2	4	actualizar	actualizar	actualizar			N	C	2	actualizar	idvista								5	frm_mant
3	4	eliminar	eliminar	eliminar			N	C	3	eliminar	idvista								5	
2	1	Actualizar	Actualizar	Actualizar	\N	\N	N	C	2	actualizar	idtabla	\N	\N	\N	\N	up_actualizartabla	idtabla,nombre,descripcion,ayuda,tipotabla	\N	1	frm_mant
1	5	nuevo	nuevo	nuevo			N	E	1	nuevo	idvista					up_agregarVista_Atributo	idvista,nombre,etiqueta,ayuda,idtabla,tipoatributo,idatributo,idregionvista,defecto,orden,css,visible,operador		9	frm_mant
2	2	actualizar	actualizar	actualizar			N	C	2	actualizar	idcampo,idtabla					up_actualizarcampo	idcampo,idtabla,nombre,descripcion,ayuda,longitud,css,idtipocontrol,idtipodato,idcombo,valores,defecto,idhandler,script_js,llave,valor_opcional		3	frm_mant
3	2	eliminar	eliminar	eliminar			N	C	3	eliminar	idoperacion,idtabla								7	content
4	4	atributos	atributos	atributos			N	C	4	listGeneral	idvista,idtabla								9	content
2	5	actualizar	actualizar	actualizar			N	C	2	actualizar	idvista_atributo,idvista								9	frm_mant
1	8	nuevo	nuevo	nuevo			N	E	1	nuevo									11	frm_mant
\.


--
-- TOC entry 1849 (class 0 OID 108486)
-- Dependencies: 1533
-- Data for Name: tabla; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY tabla (idtabla, nombre, descripcion, ayuda, tipotabla, estado) FROM stdin;
4	Vista	Vista	Vista	B	N
3	Operacion	Operación	Operación	B	N
5	vista_atributo	vista_atributo	vista_atributo	B	N
6	vista_tabla	vista_tabla	vista_tabla	B	N
2	Campo	Campo	Campo	B	N
1	Tabla	Tabla	Tabla	B	N
7	handler	handler	handler	B	N
8	TipoControl	TipoControl	TipoControl	B	N
9	TipoDato	TipoDato	TipoDato	B	N
\.


--
-- TOC entry 1851 (class 0 OID 108512)
-- Dependencies: 1535
-- Data for Name: tipocontrol; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY tipocontrol (idtipocontrol, nombre, descripcion, global) FROM stdin;
1	Caja de Texto	Caja de Texto	N
2	Caja Oculta	Caja Oculta	N
4	Lista Simple	Lista Simple	N
5	Lista Simple origen Tabla	Lista Simple Origen Tabla	N
6	Lista Simple origen Vista	Lista Simple origen Vista	N
3	Area de Texto	Area de Texto	N
7	Script JS	Script JS	S
\.


--
-- TOC entry 1856 (class 0 OID 108608)
-- Dependencies: 1540
-- Data for Name: tipodato; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY tipodato (idtipodato, nombre, descripcion, "precision") FROM stdin;
1	Character varying	Character varying	N
2	Numerico	Numerico	S
\.


--
-- TOC entry 1852 (class 0 OID 108521)
-- Dependencies: 1536
-- Data for Name: vista; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY vista (idvista, nombre, descripcion, titulo, css, idtipovista, idformato, idtabla) FROM stdin;
1	Listado tabla	Listado tabla	Listado tabla	\N	1	1	1
2	Formulario tabla	Formulario Tabla	Formulario tabka	\N	2	1	1
3	Listado campos	Listado campos	Listado campos	\N	1	1	2
4	Formulario campos	Formulario campos	Formulario campos	\N	2	1	2
5	Listado vista	Listado vista	Listado vista	\N	1	1	4
6	Formulario vista	Formulario vista	Formulario vista	\N	2	1	4
7	Listado operaciones	Listado operaciones	Listado operaciones		1	1	3
8	Formulario operaciones	Formulario operaciones	Formulario operaciones		2	1	3
9	vista_atributo	vista_atributo	vista_atributo		1	1	5
10	Formulario vista_atributo	Formulario vista_atributo	Formulario vista_atributo		2	1	5
11	TipoControl	TipoControl	TipoControl		1	1	8
\.


--
-- TOC entry 1853 (class 0 OID 108529)
-- Dependencies: 1537
-- Data for Name: vista_atributo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY vista_atributo (idvista_atributo, idvista, nombre, etiqueta, ayuda, idtabla, tipoatributo, idatributo, idregionvista, defecto, orden, css, visible, operador) FROM stdin;
1	1	idtabla	idtabla	idtabla	1	C	1	1	\N	1	\N	S	=
2	1	nombre	nombre	nombre	1	C	2	1	\N	2	\N	S	=
3	1	descripcion	descripcion	descripcion	1	C	3	1	\N	3	\N	S	=
4	1	ayuda	ayuda	ayuda	1	C	4	1	\N	4	\N	S	=
10	5	actualizar	actualizar	actualizar	4	O	2	1	\N	1	\N	S	=
11	5	eliminar	eliminar	eliminar	4	O	3	1	\N	2	\N	S	=
12	5	atributos	atributos	atributos	4	O	4	1	\N	3	\N	S	=
1	9	idvista_atributo	idvista_atributo	idvista_atributo	5	C	1	1	\N	1	\N	S	=
6	1	nombre	nombre	nombre	1	C	2	2	\N	6	\N	S	like
7	1	nuevo	nuevo	nuevo	1	O	1	3	\N	1	\N	S	=
8	1	actualizar	actualizar	actualizar	1	O	2	1	\N	7	\N	S	=
9	1	eliminar	eliminar	eliminar	1	O	3	1	\N	8	\N	S	=
1	3	idcampo	idcampo	idcampo	2	C	1	1	\N	1	\N	S	=
2	3	idtabla	idtabla	idtabla	2	C	2	1	\N	2	\N	S	=
2	9	idvista	idvista	idvista	5	C	2	1	\N	2	\N	S	=
3	3	nombre	nombre	nombre	2	C	3	1	\N	3	\N	S	=
4	3	descripcion	descripcion	descripcion	2	C	4	1	\N	4	\N	S	=
3	9	nombre	nombre	nombre	5	C	3	1	\N	3	\N	S	=
4	9	etiqueta	etiqueta	etiqueta	5	C	4	1	\N	4	\N	S	=
10	1	vercampos	Campos	ver campos	1	O	4	1	\N	9	\N	S	=
5	9	ayuda	ayuda	ayuda	5	C	5	1	\N	5	\N	S	=
9	3	idtabla	idtabla	idtabla	2	C	2	2	idtabla	1	\N	S	=
1	5	idvista	idvista	idvista	4	C	1	1	\N	1	\N	S	=
2	5	nombre	nombre	nombre	4	C	2	1	\N	2	\N	S	=
3	5	descripcion	descripcion	descripcion	4	C	3	1	\N	3	\N	S	=
4	5	titulo	titulo	titulo	4	C	4	1	\N	4	\N	S	=
5	5	idtipovista	idtipovista	idtipovista	4	C	5	1	\N	5	\N	S	=
6	5	idtabla	idtabla	idtabla	4	C	6	1	\N	6	\N	S	=
7	5	idformato	idformato	idformato	4	C	7	1	\N	7	\N	S	=
8	5	css	css	css	4	C	8	1	\N	8	\N	S	=
10	3	nuevo	Nuevo	Nuevo	2	O	1	3	\N	1	\N	S	=
11	1	veroperaciones	Operaciones	ver operaciones	1	O	5	1	\N	10	\N	S	=
9	5	nuevo	Nuevo	Nuevo	4	O	1	3	\N	1	\N	S	=
1	7	idoperacion	idoperacion	idoperacion	3	C	1	1	\N	1	\N	S	=
2	7	idtabla	idtabla	idtabla	3	C	2	1	\N	2	\N	S	=
4	7	descripcion	descripcion	descripcion	3	C	4	1	\N	4	\N	S	=
3	7	nombre	nombre	nombre	3	C	3	1	\N	3	\N	S	=
5	7	ayuda	ayuda	ayuda	3	C	5	1	\N	5	\N	S	=
6	7	css	css	css	3	C	6	1	\N	6	\N	S	=
7	7	icono	icono	icono	3	C	7	1	\N	7	\N	S	=
8	7	multiple	multiple	multiple	3	C	8	1	\N	8	\N	S	=
9	7	tipo	tipo	tipo	3	C	9	1	\N	9	\N	S	=
10	7	idhandler	idhandler	idhandler	3	C	10	1	\N	10	\N	S	=
11	7	accion	accion	accion	3	C	11	1	\N	11	\N	S	=
12	7	argumentos	argumentos	argumentos	3	C	12	1	\N	12	\N	S	=
13	7	script_js_pref	script_js_pref	script_js_pref	3	C	13	1	\N	13	\N	S	=
14	7	script_js	script_js	script_js	3	C	14	1	\N	14	\N	S	=
15	7	script_js_suf	script_js_suf	script_js_suf	3	C	15	1	\N	15	\N	S	=
16	7	script_php	script_php	script_php	3	C	16	1	\N	16	\N	S	=
17	7	function_sql	function_sql	function_sql	3	C	17	1	\N	17	\N	S	=
18	7	argumentos_sql	argumentos_sql	argumentos_sql	3	C	18	1	\N	18	\N	S	=
19	7	script_sql	script_sql	script_sql	3	C	19	1	\N	19	\N	S	=
20	7	idvista_redirect	idvista_redirect	idvista_redirect	3	C	20	1	\N	20	\N	S	=
21	7	contenedor_redirect	contenedor_redirect	contenedor_redirect	3	C	21	1	\N	21	\N	S	=
22	7	nuevo	nuevo	nuevo	3	O	1	3	\N	1	\N	S	=
23	7	idtabla	idtabla	idtabla	3	C	2	2	idtabla	1	\N	S	=
24	7	actualizar	actualizar	actualizar	3	O	2	1	\N	1	\N	S	=
6	9	idtabla	idtabla	idtabla	5	C	6	1	\N	6	\N	S	=
7	9	tipoatributo	tipoatributo	tipoatributo	5	C	7	1	\N	7	\N	S	=
8	9	idatributo	idatributo	idatributo	5	C	8	1	\N	8	\N	S	=
9	9	idregionvista	idregionvista	idregionvista	5	C	9	1	\N	9	\N	S	=
14	9	visible	visible	visible	5	C	14	1	\N	14	\N	S	=
13	9	css	css	css	5	C	13	1	\N	13	\N	S	=
12	9	orden	orden	orden	5	C	12	1	\N	12	\N	S	=
11	9	defecto	defecto	defecto	5	C	11	1	\N	11	\N	S	=
10	9	operacdor	operador	operador	5	C	10	1	\N	10	\N	S	=
15	9	idvista	idvista	idvista	5	C	2	2	idvista	1	\N	S	=
16	9	nuevo	nuevo	nuevo	5	O	1	3	\N	1	\N	S	=
5	3	ayuda	ayuda	ayuda	2	C	5	1		5		S	
11	3	idtipodato	idtipodato	idtipodato	2	C	9	1		9		S	
5	1	tipotabla	tipotabla	tipotabla	1	C	5	1	B	5	\N	S	=
12	3	Actualizar	Actualizar	Actualizar	2	O	2	1		2		S	
14	3	idcombo	idcombo	idcombo	2	C	11	1		11		S	
15	3	valores	valores	valores	2	C	12	1		12		S	
16	3	defecto	defecto	defecto	2	C	13	1		13		S	
17	3	idhandler	idhandler	idhandler	2	C	14	1		14		S	
7	3	css	css	css	2	C	7	1		7		S	
8	3	idtipocontrol	idtipocontrol	idtipocontrol	2	C	8	1		8		S	
18	3	script_js	script_js	script_js	2	C	15	1		15		S	
6	3	longitud	longitud	longitud	2	C	6	1		6		S	
19	3	llave	llave	llave	2	C	16	1		16		S	
20	3	valor_opcional	valor_opcional	valor_opcional	2	C	17	1		17		S	
17	9	actualizar	actualizar	actualizar	5	O	2	1		2		S	
1	11	idtipocontrol	idtipocontrol	idtipocontrol	8	C	1	1		1		S	
2	11	nombre	nombre	nombre	8	C	2	1		2		S	
3	11	descripcion	descripcion	descripcion	8	C	3	1		3		S	
4	11	global	global	global	8	C	4	1		4		S	
5	11	nuevo	nuevo	nuevo	8	O	1	3		1		S	
\.


--
-- TOC entry 1846 (class 2606 OID 108633)
-- Dependencies: 1541 1541 1541
-- Name: pk_atributo; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY atributo_control
    ADD CONSTRAINT pk_atributo PRIMARY KEY (idatributo_control, idtipocontrol);


--
-- TOC entry 1832 (class 2606 OID 108511)
-- Dependencies: 1534 1534 1534
-- Name: pk_campo; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY campo
    ADD CONSTRAINT pk_campo PRIMARY KEY (idcampo, idtabla);


--
-- TOC entry 1848 (class 2606 OID 108643)
-- Dependencies: 1542 1542 1542 1542 1542 1542
-- Name: pk_campo_atributo; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY campo_atributo
    ADD CONSTRAINT pk_campo_atributo PRIMARY KEY (idcampo_atributo, idtabla, idcampo, idatributo_control, idtipocontrol);


--
-- TOC entry 1842 (class 2606 OID 108580)
-- Dependencies: 1539 1539
-- Name: pk_handler; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY handler
    ADD CONSTRAINT pk_handler PRIMARY KEY (idhandler);


--
-- TOC entry 1830 (class 2606 OID 108490)
-- Dependencies: 1533 1533
-- Name: pk_idtabla; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tabla
    ADD CONSTRAINT pk_idtabla PRIMARY KEY (idtabla);


--
-- TOC entry 1840 (class 2606 OID 108567)
-- Dependencies: 1538 1538 1538
-- Name: pk_operacion; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY operacion
    ADD CONSTRAINT pk_operacion PRIMARY KEY (idoperacion, idtabla);


--
-- TOC entry 1834 (class 2606 OID 108517)
-- Dependencies: 1535 1535
-- Name: pk_tipocontrol; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tipocontrol
    ADD CONSTRAINT pk_tipocontrol PRIMARY KEY (idtipocontrol);


--
-- TOC entry 1844 (class 2606 OID 108613)
-- Dependencies: 1540 1540
-- Name: pk_tipodato; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tipodato
    ADD CONSTRAINT pk_tipodato PRIMARY KEY (idtipodato);


--
-- TOC entry 1836 (class 2606 OID 108528)
-- Dependencies: 1536 1536
-- Name: pk_vista; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY vista
    ADD CONSTRAINT pk_vista PRIMARY KEY (idvista);


--
-- TOC entry 1838 (class 2606 OID 108545)
-- Dependencies: 1537 1537 1537
-- Name: pk_vista_atribute; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY vista_atributo
    ADD CONSTRAINT pk_vista_atribute PRIMARY KEY (idvista_atributo, idvista);


--
-- TOC entry 1863 (class 0 OID 0)
-- Dependencies: 5
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2014-08-26 11:58:45

--
-- PostgreSQL database dump complete
--


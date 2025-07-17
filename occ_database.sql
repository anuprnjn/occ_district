--
-- PostgreSQL database dump
--

-- Dumped from database version 17.2
-- Dumped by pg_dump version 17.2

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: _occ_database; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA _occ_database;


ALTER SCHEMA _occ_database OWNER TO postgres;

--
-- Name: enum123; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.enum123 AS ENUM (
    '1',
    '2',
    '3'
);


ALTER TYPE public.enum123 OWNER TO postgres;

--
-- Name: enumyn; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.enumyn AS ENUM (
    'Y',
    'N'
);


ALTER TYPE public.enumyn OWNER TO postgres;

--
-- Name: log_changes(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.log_changes() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    INSERT INTO table_log (table_name, operation_type, old_data, new_data)
    VALUES (TG_TABLE_NAME, TG_OP, row_to_json(OLD), row_to_json(NEW));
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.log_changes() OWNER TO postgres;

--
-- Name: set_default_est_code(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.set_default_est_code() RETURNS character
    LANGUAGE plpgsql
    AS $$
BEGIN
    RETURN 'JHCH05'; -- Replace 'JHCH05' with your desired default value
END;
$$;


ALTER FUNCTION public.set_default_est_code() OWNER TO postgres;

--
-- Name: update_updated_at_column(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.update_updated_at_column() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.update_updated_at_column() OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: cache; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache OWNER TO postgres;

--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO postgres;

--
-- Name: civil_court_users_master; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.civil_court_users_master (
    id integer NOT NULL,
    user_name character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    mobile_number character varying(20) NOT NULL,
    password text NOT NULL,
    district_code character varying(50) NOT NULL,
    role_id integer NOT NULL
);


ALTER TABLE public.civil_court_users_master OWNER TO postgres;

--
-- Name: civil_court_users_master_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.civil_court_users_master_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.civil_court_users_master_id_seq OWNER TO postgres;

--
-- Name: civil_court_users_master_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.civil_court_users_master_id_seq OWNED BY public.civil_court_users_master.id;


--
-- Name: civilcourt_applicant_document_detail; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.civilcourt_applicant_document_detail (
    id bigint NOT NULL,
    application_number character varying(200),
    document_type character varying(200),
    number_of_page integer NOT NULL,
    amount numeric(10,2) NOT NULL,
    file_name character varying(255),
    upload_status boolean DEFAULT false,
    uploaded_by integer,
    uploaded_date timestamp without time zone,
    certified_copy_file_name character varying(255),
    certified_copy_upload_status boolean DEFAULT false,
    certified_copy_uploaded_by integer,
    certified_copy_uploaded_date timestamp without time zone,
    created_at timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.civilcourt_applicant_document_detail OWNER TO postgres;

--
-- Name: civilcourt_applicant_document_detail_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.civilcourt_applicant_document_detail_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.civilcourt_applicant_document_detail_id_seq OWNER TO postgres;

--
-- Name: civilcourt_applicant_document_detail_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.civilcourt_applicant_document_detail_id_seq OWNED BY public.civilcourt_applicant_document_detail.id;


--
-- Name: dc_users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.dc_users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    mobile_no character varying(10) NOT NULL,
    role_id integer NOT NULL,
    username character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    dist_code integer,
    created_at timestamp without time zone DEFAULT now()
);


ALTER TABLE public.dc_users OWNER TO postgres;

--
-- Name: dc_users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.dc_users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.dc_users_id_seq OWNER TO postgres;

--
-- Name: dc_users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.dc_users_id_seq OWNED BY public.dc_users.id;


--
-- Name: district_application_number_tracker; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.district_application_number_tracker (
    id bigint NOT NULL,
    date_key date NOT NULL,
    counter integer DEFAULT 0 NOT NULL,
    dist_code integer NOT NULL
);


ALTER TABLE public.district_application_number_tracker OWNER TO postgres;

--
-- Name: district_application_number_tracker_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.district_application_number_tracker_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.district_application_number_tracker_id_seq OWNER TO postgres;

--
-- Name: district_application_number_tracker_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.district_application_number_tracker_id_seq OWNED BY public.district_application_number_tracker.id;


--
-- Name: district_court_applicant_registration; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.district_court_applicant_registration (
    application_id integer NOT NULL,
    application_number character varying(50) NOT NULL,
    district_code integer NOT NULL,
    establishment_code character varying(50) NOT NULL,
    applicant_name character varying(100) NOT NULL,
    mobile_number character varying(15) NOT NULL,
    email character varying(100) NOT NULL,
    case_type integer NOT NULL,
    case_filling_number character varying(50) NOT NULL,
    case_filling_year integer NOT NULL,
    selected_method character varying(50) NOT NULL,
    request_mode character varying(50) NOT NULL,
    required_document text NOT NULL,
    applied_by character varying(50) NOT NULL,
    advocate_registration_number character varying(50),
    document_status integer DEFAULT 0,
    payment_status integer DEFAULT 3,
    certified_copy_ready_status integer DEFAULT 0,
    rejection_status integer DEFAULT 0,
    rejection_remarks text,
    rejected_by integer DEFAULT 0,
    rejection_date timestamp without time zone,
    created_by character varying(50) NOT NULL,
    updated_by character varying(50),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.district_court_applicant_registration OWNER TO postgres;

--
-- Name: district_court_applicant_registration_application_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.district_court_applicant_registration_application_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.district_court_applicant_registration_application_id_seq OWNER TO postgres;

--
-- Name: district_court_applicant_registration_application_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.district_court_applicant_registration_application_id_seq OWNED BY public.district_court_applicant_registration.application_id;


--
-- Name: district_court_case_master; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.district_court_case_master (
    case_type bigint,
    type_name character varying(200),
    est_code character varying(50)
);


ALTER TABLE public.district_court_case_master OWNER TO postgres;

--
-- Name: district_court_order_copy_applicant_registration; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.district_court_order_copy_applicant_registration (
    application_id integer NOT NULL,
    application_number character varying(50) NOT NULL,
    cino character varying(50) NOT NULL,
    district_code integer NOT NULL,
    establishment_code character varying(50) NOT NULL,
    applicant_name character varying(100) NOT NULL,
    mobile_number character varying(15) NOT NULL,
    email character varying(100) NOT NULL,
    case_type integer NOT NULL,
    case_number character varying(50),
    case_year character varying(5),
    filing_number character varying(50),
    filing_year character varying(5),
    case_status character varying(5),
    request_mode character varying(50) NOT NULL,
    applied_by character varying(50) NOT NULL,
    advocate_registration_number character varying(50),
    petitioner_name character varying(255) DEFAULT 'NA'::character varying,
    respondent_name character varying(255) DEFAULT 'NA'::character varying,
    document_status integer DEFAULT 0,
    deficit_amount numeric(10,2),
    deficit_status integer DEFAULT 0,
    deficit_payment_status integer DEFAULT 0,
    payment_status integer DEFAULT 3,
    certified_copy_ready_status integer DEFAULT 0,
    user_id integer DEFAULT 0,
    created_by character varying(50) NOT NULL,
    updated_by character varying(50),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.district_court_order_copy_applicant_registration OWNER TO postgres;

--
-- Name: district_court_order_copy_applicant_registra_application_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.district_court_order_copy_applicant_registra_application_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.district_court_order_copy_applicant_registra_application_id_seq OWNER TO postgres;

--
-- Name: district_court_order_copy_applicant_registra_application_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.district_court_order_copy_applicant_registra_application_id_seq OWNED BY public.district_court_order_copy_applicant_registration.application_id;


--
-- Name: district_court_order_copy_application_number_tracker; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.district_court_order_copy_application_number_tracker (
    id bigint NOT NULL,
    date_key date NOT NULL,
    counter integer DEFAULT 0 NOT NULL,
    dist_code integer NOT NULL
);


ALTER TABLE public.district_court_order_copy_application_number_tracker OWNER TO postgres;

--
-- Name: district_court_order_copy_application_number_tracker_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.district_court_order_copy_application_number_tracker_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.district_court_order_copy_application_number_tracker_id_seq OWNER TO postgres;

--
-- Name: district_court_order_copy_application_number_tracker_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.district_court_order_copy_application_number_tracker_id_seq OWNED BY public.district_court_order_copy_application_number_tracker.id;


--
-- Name: district_court_order_details; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.district_court_order_details (
    order_id integer NOT NULL,
    application_number character varying(50) NOT NULL,
    order_number smallint NOT NULL,
    order_date date NOT NULL,
    case_number character varying(50),
    filing_number character varying(50),
    number_of_page integer NOT NULL,
    amount numeric(10,2) NOT NULL,
    file_name character varying(255),
    upload_status boolean DEFAULT false,
    certified_copy_uploaded_date timestamp without time zone,
    new_page_no integer DEFAULT 0,
    new_page_amount numeric(10,2)
);


ALTER TABLE public.district_court_order_details OWNER TO postgres;

--
-- Name: district_court_order_details_order_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.district_court_order_details_order_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.district_court_order_details_order_id_seq OWNER TO postgres;

--
-- Name: district_court_order_details_order_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.district_court_order_details_order_id_seq OWNED BY public.district_court_order_details.order_id;


--
-- Name: district_master; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.district_master (
    dist_code bigint,
    dist_name character varying(200)
);


ALTER TABLE public.district_master OWNER TO postgres;

--
-- Name: districts_case_type; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.districts_case_type (
    case_type smallint DEFAULT 0 NOT NULL,
    type_name character varying(50) DEFAULT NULL::character varying,
    ltype_name character varying(50) DEFAULT NULL::character varying,
    full_form character varying(100) DEFAULT NULL::character varying,
    lfull_form character varying(100) DEFAULT NULL::character varying,
    type_flag public.enum123 DEFAULT '1'::public.enum123 NOT NULL,
    filing_no integer DEFAULT 0 NOT NULL,
    filing_year smallint DEFAULT 0 NOT NULL,
    reg_no integer DEFAULT 0 NOT NULL,
    reg_year smallint DEFAULT 0 NOT NULL,
    display public.enumyn DEFAULT 'Y'::public.enumyn NOT NULL,
    petitioner character varying(99) DEFAULT NULL::character varying,
    respondent character varying(99) DEFAULT NULL::character varying,
    lpetitioner character varying(99) DEFAULT NULL::character varying,
    lrespondent character varying(99) DEFAULT NULL::character varying,
    res_disp smallint DEFAULT 0 NOT NULL,
    case_priority smallint DEFAULT 0 NOT NULL,
    national_code bigint DEFAULT 0 NOT NULL,
    macp public.enumyn DEFAULT 'N'::public.enumyn NOT NULL,
    stage_id text,
    matter_type integer DEFAULT 0,
    cavreg_no integer DEFAULT 0 NOT NULL,
    cavreg_year smallint DEFAULT 0 NOT NULL,
    direct_reg public.enumyn DEFAULT 'N'::public.enumyn NOT NULL,
    cavfil_no integer DEFAULT 0 NOT NULL,
    cavfil_year smallint DEFAULT 0 NOT NULL,
    ia_filing_no integer DEFAULT 0 NOT NULL,
    ia_filing_year smallint DEFAULT 0 NOT NULL,
    ia_reg_no integer DEFAULT 0 NOT NULL,
    ia_reg_year smallint DEFAULT 0 NOT NULL,
    tag_courts character varying(1000),
    amd character(1),
    create_modify timestamp without time zone DEFAULT now(),
    est_code_src character(6) DEFAULT public.set_default_est_code() NOT NULL,
    reasonable_dispose text,
    hideparty character(1) DEFAULT 'N'::bpchar NOT NULL,
    imovable_suit public.enumyn DEFAULT 'N'::public.enumyn,
    sec_hash_key character varying(200),
    case_type_jurisdiction public.enumyn DEFAULT 'Y'::public.enumyn NOT NULL
);


ALTER TABLE public.districts_case_type OWNER TO postgres;

--
-- Name: establishment; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.establishment (
    id bigint NOT NULL,
    est_code character varying(50),
    estname character varying(100),
    dist_code integer
);


ALTER TABLE public.establishment OWNER TO postgres;

--
-- Name: establishment_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.establishment_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.establishment_id_seq OWNER TO postgres;

--
-- Name: establishment_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.establishment_id_seq OWNED BY public.establishment.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO postgres;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO postgres;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: fee_master; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.fee_master (
    fee_id integer NOT NULL,
    fee_type character varying(50) NOT NULL,
    amount numeric(10,2) NOT NULL
);


ALTER TABLE public.fee_master OWNER TO postgres;

--
-- Name: fee_master_fee_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.fee_master_fee_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.fee_master_fee_id_seq OWNER TO postgres;

--
-- Name: fee_master_fee_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.fee_master_fee_id_seq OWNED BY public.fee_master.fee_id;


--
-- Name: hc_order_copy_applicant_registration; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.hc_order_copy_applicant_registration (
    application_id integer NOT NULL,
    application_number character varying(50) NOT NULL,
    cino character varying(50) NOT NULL,
    applicant_name character varying(100) NOT NULL,
    mobile_number character varying(15) NOT NULL,
    email character varying(100) NOT NULL,
    case_type integer NOT NULL,
    case_number character varying(50),
    case_year character varying(5),
    filing_number character varying(50),
    filing_year character varying(5),
    case_status character varying(5),
    request_mode character varying(50) NOT NULL,
    applied_by character varying(50) NOT NULL,
    advocate_registration_number character varying(50),
    petitioner_name character varying(255) DEFAULT 'NA'::character varying,
    respondent_name character varying(255) DEFAULT 'NA'::character varying,
    document_status integer DEFAULT 0,
    deficit_amount numeric(10,2),
    deficit_status integer DEFAULT 0,
    deficit_payment_status integer DEFAULT 0,
    payment_status integer DEFAULT 3,
    certified_copy_ready_status integer DEFAULT 0,
    user_id integer DEFAULT 0,
    created_by character varying(50) NOT NULL,
    updated_by character varying(50),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.hc_order_copy_applicant_registration OWNER TO postgres;

--
-- Name: hc_order_copy_applicant_registration_application_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.hc_order_copy_applicant_registration_application_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.hc_order_copy_applicant_registration_application_id_seq OWNER TO postgres;

--
-- Name: hc_order_copy_applicant_registration_application_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.hc_order_copy_applicant_registration_application_id_seq OWNED BY public.hc_order_copy_applicant_registration.application_id;


--
-- Name: hc_order_copy_application_number_tracker; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.hc_order_copy_application_number_tracker (
    date_key date NOT NULL,
    counter integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.hc_order_copy_application_number_tracker OWNER TO postgres;

--
-- Name: hc_order_details; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.hc_order_details (
    order_id integer NOT NULL,
    application_number character varying(50) NOT NULL,
    order_number smallint NOT NULL,
    order_date date NOT NULL,
    case_number character varying(50),
    filing_number character varying(50),
    number_of_page integer NOT NULL,
    amount numeric(10,2) NOT NULL,
    file_name character varying(255),
    upload_status boolean DEFAULT false,
    certified_copy_uploaded_date timestamp without time zone,
    new_page_no integer DEFAULT 0,
    new_page_amount numeric(10,2)
);


ALTER TABLE public.hc_order_details OWNER TO postgres;

--
-- Name: hc_order_details_order_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.hc_order_details_order_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.hc_order_details_order_id_seq OWNER TO postgres;

--
-- Name: hc_order_details_order_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.hc_order_details_order_id_seq OWNED BY public.hc_order_details.order_id;


--
-- Name: hc_users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.hc_users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    mobile_no character varying(10) NOT NULL,
    role_id integer NOT NULL,
    username character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    created_at timestamp without time zone DEFAULT now()
);


ALTER TABLE public.hc_users OWNER TO postgres;

--
-- Name: hc_users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.hc_users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.hc_users_id_seq OWNER TO postgres;

--
-- Name: hc_users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.hc_users_id_seq OWNED BY public.hc_users.id;


--
-- Name: high_court_applicant_registration; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.high_court_applicant_registration (
    application_id integer NOT NULL,
    application_number character varying(50) NOT NULL,
    applicant_name character varying(100) NOT NULL,
    mobile_number character varying(15) NOT NULL,
    email character varying(100) NOT NULL,
    case_type integer NOT NULL,
    case_filling_number character varying(50) NOT NULL,
    case_filling_year integer NOT NULL,
    selected_method character varying(50) NOT NULL,
    request_mode character varying(50) NOT NULL,
    required_document text NOT NULL,
    applied_by character varying(50) NOT NULL,
    advocate_registration_number character varying(50),
    document_status integer DEFAULT 0,
    payment_status integer DEFAULT 3,
    certified_copy_ready_status integer DEFAULT 0,
    rejection_status integer DEFAULT 0,
    rejection_remarks text,
    rejected_by integer DEFAULT 0,
    rejection_date timestamp without time zone,
    created_by character varying(50) NOT NULL,
    updated_by character varying(50),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.high_court_applicant_registration OWNER TO postgres;

--
-- Name: high_court_applicant_registration_application_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.high_court_applicant_registration_application_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.high_court_applicant_registration_application_id_seq OWNER TO postgres;

--
-- Name: high_court_applicant_registration_application_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.high_court_applicant_registration_application_id_seq OWNED BY public.high_court_applicant_registration.application_id;


--
-- Name: high_court_application_number_tracker; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.high_court_application_number_tracker (
    date_key date NOT NULL,
    counter integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.high_court_application_number_tracker OWNER TO postgres;

--
-- Name: high_court_case_master; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.high_court_case_master (
    case_type bigint,
    type_name character varying(200)
);


ALTER TABLE public.high_court_case_master OWNER TO postgres;

--
-- Name: high_court_case_type; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.high_court_case_type (
    case_type smallint DEFAULT 0 NOT NULL,
    type_name character varying(50) DEFAULT NULL::character varying,
    ltype_name character varying(50) DEFAULT NULL::character varying,
    full_form character varying(100) DEFAULT NULL::character varying,
    lfull_form character varying(100) DEFAULT NULL::character varying,
    type_flag public.enum123 DEFAULT '1'::public.enum123 NOT NULL,
    filing_no integer DEFAULT 0 NOT NULL,
    filing_year smallint DEFAULT 0 NOT NULL,
    reg_no integer DEFAULT 0 NOT NULL,
    reg_year smallint DEFAULT 0 NOT NULL,
    display public.enumyn DEFAULT 'Y'::public.enumyn NOT NULL,
    petitioner character varying(99) DEFAULT NULL::character varying,
    respondent character varying(99) DEFAULT NULL::character varying,
    lpetitioner character varying(99) DEFAULT NULL::character varying,
    lrespondent character varying(99) DEFAULT NULL::character varying,
    res_disp smallint DEFAULT 0 NOT NULL,
    case_priority smallint DEFAULT 0 NOT NULL,
    national_code bigint DEFAULT 0 NOT NULL,
    macp public.enumyn DEFAULT 'N'::public.enumyn NOT NULL,
    stage_id text,
    matter_type integer DEFAULT 0,
    cavreg_no integer DEFAULT 0 NOT NULL,
    cavreg_year smallint DEFAULT 0 NOT NULL,
    direct_reg public.enumyn DEFAULT 'N'::public.enumyn NOT NULL,
    cavfil_no integer DEFAULT 0 NOT NULL,
    cavfil_year smallint DEFAULT 0 NOT NULL,
    ia_filing_no integer DEFAULT 0 NOT NULL,
    ia_filing_year smallint DEFAULT 0 NOT NULL,
    ia_reg_no integer DEFAULT 0 NOT NULL,
    ia_reg_year smallint DEFAULT 0 NOT NULL,
    tag_courts character varying(1000),
    amd character(1),
    create_modify timestamp without time zone DEFAULT now(),
    reasonable_dispose text,
    hideparty character(1) DEFAULT 'N'::bpchar NOT NULL,
    est_code_src character(6) DEFAULT public.set_default_est_code() NOT NULL
);


ALTER TABLE public.high_court_case_type OWNER TO postgres;

--
-- Name: high_court_transaction_number_tracker; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.high_court_transaction_number_tracker (
    date_key date NOT NULL,
    counter integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.high_court_transaction_number_tracker OWNER TO postgres;

--
-- Name: high_court_users_master; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.high_court_users_master (
    id integer NOT NULL,
    user_name character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    mobile character varying(20) NOT NULL,
    password text NOT NULL,
    role_id integer NOT NULL
);


ALTER TABLE public.high_court_users_master OWNER TO postgres;

--
-- Name: high_court_users_master_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.high_court_users_master_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.high_court_users_master_id_seq OWNER TO postgres;

--
-- Name: high_court_users_master_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.high_court_users_master_id_seq OWNED BY public.high_court_users_master.id;


--
-- Name: highcourt_applicant_document_detail; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.highcourt_applicant_document_detail (
    id bigint NOT NULL,
    application_number character varying(200),
    document_type character varying(200),
    number_of_page integer NOT NULL,
    amount numeric(10,2) NOT NULL,
    file_name character varying(255),
    upload_status boolean DEFAULT false,
    uploaded_by integer,
    uploaded_date timestamp without time zone,
    certified_copy_file_name character varying(255),
    certified_copy_upload_status boolean DEFAULT false,
    certified_copy_uploaded_by integer,
    certified_copy_uploaded_date timestamp without time zone,
    created_at timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.highcourt_applicant_document_detail OWNER TO postgres;

--
-- Name: highcourt_applicant_document_detail_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.highcourt_applicant_document_detail_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.highcourt_applicant_document_detail_id_seq OWNER TO postgres;

--
-- Name: highcourt_applicant_document_detail_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.highcourt_applicant_document_detail_id_seq OWNED BY public.highcourt_applicant_document_detail.id;


--
-- Name: jegras_merchant_details_dc; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jegras_merchant_details_dc (
    id bigint NOT NULL,
    dist_code bigint,
    deptid character varying(20),
    recieptheadcode character varying(15),
    treascode character varying(3),
    ifmsofficecode character varying(9),
    securitycode character varying(10)
);


ALTER TABLE public.jegras_merchant_details_dc OWNER TO postgres;

--
-- Name: jegras_merchant_details_dc_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.jegras_merchant_details_dc_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jegras_merchant_details_dc_id_seq OWNER TO postgres;

--
-- Name: jegras_merchant_details_dc_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.jegras_merchant_details_dc_id_seq OWNED BY public.jegras_merchant_details_dc.id;


--
-- Name: jegras_merchant_details_hc; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jegras_merchant_details_hc (
    id bigint NOT NULL,
    deptid character varying(20),
    recieptheadcode character varying(15),
    treascode character varying(3),
    ifmsofficecode character varying(9),
    securitycode character varying(10)
);


ALTER TABLE public.jegras_merchant_details_hc OWNER TO postgres;

--
-- Name: jegras_merchant_details_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.jegras_merchant_details_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jegras_merchant_details_id_seq OWNER TO postgres;

--
-- Name: jegras_merchant_details_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.jegras_merchant_details_id_seq OWNED BY public.jegras_merchant_details_hc.id;


--
-- Name: jegras_payment_request_dc; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jegras_payment_request_dc (
    deptid character varying(20),
    application_number character varying(50) NOT NULL,
    recieptheadcode character varying(15),
    depositername character varying(50),
    depttranid character varying(19) NOT NULL,
    amount numeric(10,2),
    depositerid character varying(10),
    panno character varying(10),
    addinfo1 character varying(50),
    addinfo2 character varying(50),
    addinfo3 character varying(50),
    treascode character varying(3),
    ifmsofficecode character varying(9),
    securitycode character varying(15),
    response_url character varying(50),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    establishment_code character varying(20),
    district_code character varying(20)
);


ALTER TABLE public.jegras_payment_request_dc OWNER TO postgres;

--
-- Name: jegras_payment_request_hc; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jegras_payment_request_hc (
    deptid character varying(20),
    application_number character varying(50) NOT NULL,
    recieptheadcode character varying(15),
    depositername character varying(50),
    depttranid character varying(19) NOT NULL,
    amount numeric(10,2),
    depositerid character varying(10),
    panno character varying(10),
    addinfo1 character varying(50),
    addinfo2 character varying(50),
    addinfo3 character varying(50),
    treascode character varying(3),
    ifmsofficecode character varying(9),
    securitycode character varying(15),
    response_url character varying(50),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.jegras_payment_request_hc OWNER TO postgres;

--
-- Name: jegras_payment_response_dc; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jegras_payment_response_dc (
    deptid character varying(20),
    application_number character varying(50) NOT NULL,
    recieptheadcode character varying(15),
    depositername character varying(50),
    depttranid character varying(19) NOT NULL,
    amount numeric(10,2),
    depositerid character varying(10),
    panno character varying(10),
    addinfo1 character varying(50),
    addinfo2 character varying(50),
    addinfo3 character varying(50),
    treascode character varying(3),
    ifmsofficecode character varying(9),
    status character varying(10),
    paymentstatusmessage character varying(200),
    grn character varying(10),
    cin character varying(50),
    ref_no character varying(50),
    txn_date date,
    txn_amount numeric(10,2),
    challan_url character varying(500),
    pmode character varying(100),
    addinfo4 character varying(50),
    addinfo5 character varying(200)
);


ALTER TABLE public.jegras_payment_response_dc OWNER TO postgres;

--
-- Name: jegras_payment_response_hc; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jegras_payment_response_hc (
    deptid character varying(20),
    application_number character varying(50) NOT NULL,
    recieptheadcode character varying(15),
    depositername character varying(50),
    depttranid character varying(19) NOT NULL,
    amount numeric(10,2),
    depositerid character varying(10),
    panno character varying(10),
    addinfo1 character varying(50),
    addinfo2 character varying(50),
    addinfo3 character varying(50),
    treascode character varying(3),
    ifmsofficecode character varying(9),
    status character varying(10),
    paymentstatusmessage character varying(200),
    grn character varying(10),
    cin character varying(50),
    ref_no character varying(50),
    txn_date date,
    txn_amount numeric(10,2),
    challan_url character varying(500),
    pmode character varying(100),
    addinfo4 character varying(50),
    addinfo5 character varying(200)
);


ALTER TABLE public.jegras_payment_response_hc OWNER TO postgres;

--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


ALTER TABLE public.job_batches OWNER TO postgres;

--
-- Name: jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO postgres;

--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jobs_id_seq OWNER TO postgres;

--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: log_activity_dc; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.log_activity_dc (
    id bigint NOT NULL,
    log_desc text,
    log_date timestamp without time zone,
    log_action character varying(100),
    user_id integer,
    dist_code integer,
    est_code character varying(10),
    username character varying(200)
);


ALTER TABLE public.log_activity_dc OWNER TO postgres;

--
-- Name: log_activity_dc_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.log_activity_dc_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.log_activity_dc_id_seq OWNER TO postgres;

--
-- Name: log_activity_dc_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.log_activity_dc_id_seq OWNED BY public.log_activity_dc.id;


--
-- Name: log_activity_hc; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.log_activity_hc (
    id bigint NOT NULL,
    log_desc text,
    log_date timestamp without time zone,
    log_action character varying(100),
    user_id integer,
    username character varying(200)
);


ALTER TABLE public.log_activity_hc OWNER TO postgres;

--
-- Name: log_activity_hc_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.log_activity_hc_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.log_activity_hc_id_seq OWNER TO postgres;

--
-- Name: log_activity_hc_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.log_activity_hc_id_seq OWNED BY public.log_activity_hc.id;


--
-- Name: menu_master; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.menu_master (
    menu_id integer NOT NULL,
    menu_name character varying(255) NOT NULL,
    menu_icon character varying(255)
);


ALTER TABLE public.menu_master OWNER TO postgres;

--
-- Name: menu_master_menu_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.menu_master_menu_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.menu_master_menu_id_seq OWNER TO postgres;

--
-- Name: menu_master_menu_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.menu_master_menu_id_seq OWNED BY public.menu_master.menu_id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO postgres;

--
-- Name: role_master; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.role_master (
    role_id integer NOT NULL,
    role_name character varying(255) NOT NULL
);


ALTER TABLE public.role_master OWNER TO postgres;

--
-- Name: role_master_role_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.role_master_role_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.role_master_role_id_seq OWNER TO postgres;

--
-- Name: role_master_role_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.role_master_role_id_seq OWNED BY public.role_master.role_id;


--
-- Name: role_permissions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.role_permissions (
    id integer NOT NULL,
    role_id integer NOT NULL,
    menu_id integer NOT NULL,
    submenu_id integer NOT NULL
);


ALTER TABLE public.role_permissions OWNER TO postgres;

--
-- Name: role_permissions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.role_permissions_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.role_permissions_id_seq OWNER TO postgres;

--
-- Name: role_permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.role_permissions_id_seq OWNED BY public.role_permissions.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO postgres;

--
-- Name: submenu_master; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.submenu_master (
    submenu_id bigint NOT NULL,
    menu_id bigint,
    submenu_name character varying(255) NOT NULL,
    url character varying(1000) NOT NULL
);


ALTER TABLE public.submenu_master OWNER TO postgres;

--
-- Name: submenu_master_submenu_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

ALTER TABLE public.submenu_master ALTER COLUMN submenu_id ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.submenu_master_submenu_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);


--
-- Name: table_log; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.table_log (
    id integer NOT NULL,
    table_name text,
    operation_type text,
    old_data jsonb,
    new_data jsonb,
    changed_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.table_log OWNER TO postgres;

--
-- Name: table_log_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.table_log_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.table_log_id_seq OWNER TO postgres;

--
-- Name: table_log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.table_log_id_seq OWNED BY public.table_log.id;


--
-- Name: transaction_master_dc_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.transaction_master_dc_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.transaction_master_dc_id_seq OWNER TO postgres;

--
-- Name: transaction_master_dc; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.transaction_master_dc (
    id bigint DEFAULT nextval('public.transaction_master_dc_id_seq'::regclass) NOT NULL,
    application_number character varying(50) NOT NULL,
    transaction_no character varying(50),
    amount numeric(10,2),
    urgent_fee numeric(10,2),
    transaction_date character varying(100),
    payment_status integer DEFAULT 3,
    transaction_status character varying(50),
    paymentstatusmessage character varying(500),
    depositer_id character varying(50),
    deficit_payment integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    double_verification integer DEFAULT 0,
    district_code character varying(20) DEFAULT 20,
    establishment_code character varying(20)
);


ALTER TABLE public.transaction_master_dc OWNER TO postgres;

--
-- Name: transaction_master_hc; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.transaction_master_hc (
    id bigint NOT NULL,
    application_number character varying(50) NOT NULL,
    transaction_no character varying(50),
    amount numeric(10,2),
    urgent_fee numeric(10,2),
    transaction_date character varying(100),
    payment_status integer DEFAULT 3,
    transaction_status character varying(50),
    paymentstatusmessage character varying(500),
    depositer_id character varying(50),
    deficit_payment integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    double_verification integer DEFAULT 0
);


ALTER TABLE public.transaction_master_hc OWNER TO postgres;

--
-- Name: transaction_master_hc_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.transaction_master_hc_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.transaction_master_hc_id_seq OWNER TO postgres;

--
-- Name: transaction_master_hc_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.transaction_master_hc_id_seq OWNED BY public.transaction_master_hc.id;


--
-- Name: user_establishments; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.user_establishments (
    id bigint NOT NULL,
    user_id integer NOT NULL,
    est_code character varying(255) NOT NULL,
    role_id integer NOT NULL,
    dist_code character varying(255) NOT NULL
);


ALTER TABLE public.user_establishments OWNER TO postgres;

--
-- Name: user_establishments_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.user_establishments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.user_establishments_id_seq OWNER TO postgres;

--
-- Name: user_establishments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.user_establishments_id_seq OWNED BY public.user_establishments.id;


--
-- Name: civil_court_users_master id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.civil_court_users_master ALTER COLUMN id SET DEFAULT nextval('public.civil_court_users_master_id_seq'::regclass);


--
-- Name: civilcourt_applicant_document_detail id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.civilcourt_applicant_document_detail ALTER COLUMN id SET DEFAULT nextval('public.civilcourt_applicant_document_detail_id_seq'::regclass);


--
-- Name: dc_users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dc_users ALTER COLUMN id SET DEFAULT nextval('public.dc_users_id_seq'::regclass);


--
-- Name: district_application_number_tracker id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.district_application_number_tracker ALTER COLUMN id SET DEFAULT nextval('public.district_application_number_tracker_id_seq'::regclass);


--
-- Name: district_court_applicant_registration application_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.district_court_applicant_registration ALTER COLUMN application_id SET DEFAULT nextval('public.district_court_applicant_registration_application_id_seq'::regclass);


--
-- Name: district_court_order_copy_applicant_registration application_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.district_court_order_copy_applicant_registration ALTER COLUMN application_id SET DEFAULT nextval('public.district_court_order_copy_applicant_registra_application_id_seq'::regclass);


--
-- Name: district_court_order_copy_application_number_tracker id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.district_court_order_copy_application_number_tracker ALTER COLUMN id SET DEFAULT nextval('public.district_court_order_copy_application_number_tracker_id_seq'::regclass);


--
-- Name: district_court_order_details order_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.district_court_order_details ALTER COLUMN order_id SET DEFAULT nextval('public.district_court_order_details_order_id_seq'::regclass);


--
-- Name: establishment id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.establishment ALTER COLUMN id SET DEFAULT nextval('public.establishment_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: fee_master fee_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.fee_master ALTER COLUMN fee_id SET DEFAULT nextval('public.fee_master_fee_id_seq'::regclass);


--
-- Name: hc_order_copy_applicant_registration application_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.hc_order_copy_applicant_registration ALTER COLUMN application_id SET DEFAULT nextval('public.hc_order_copy_applicant_registration_application_id_seq'::regclass);


--
-- Name: hc_order_details order_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.hc_order_details ALTER COLUMN order_id SET DEFAULT nextval('public.hc_order_details_order_id_seq'::regclass);


--
-- Name: hc_users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.hc_users ALTER COLUMN id SET DEFAULT nextval('public.hc_users_id_seq'::regclass);


--
-- Name: high_court_applicant_registration application_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.high_court_applicant_registration ALTER COLUMN application_id SET DEFAULT nextval('public.high_court_applicant_registration_application_id_seq'::regclass);


--
-- Name: high_court_users_master id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.high_court_users_master ALTER COLUMN id SET DEFAULT nextval('public.high_court_users_master_id_seq'::regclass);


--
-- Name: highcourt_applicant_document_detail id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.highcourt_applicant_document_detail ALTER COLUMN id SET DEFAULT nextval('public.highcourt_applicant_document_detail_id_seq'::regclass);


--
-- Name: jegras_merchant_details_dc id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jegras_merchant_details_dc ALTER COLUMN id SET DEFAULT nextval('public.jegras_merchant_details_dc_id_seq'::regclass);


--
-- Name: jegras_merchant_details_hc id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jegras_merchant_details_hc ALTER COLUMN id SET DEFAULT nextval('public.jegras_merchant_details_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: log_activity_dc id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.log_activity_dc ALTER COLUMN id SET DEFAULT nextval('public.log_activity_dc_id_seq'::regclass);


--
-- Name: log_activity_hc id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.log_activity_hc ALTER COLUMN id SET DEFAULT nextval('public.log_activity_hc_id_seq'::regclass);


--
-- Name: menu_master menu_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.menu_master ALTER COLUMN menu_id SET DEFAULT nextval('public.menu_master_menu_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: role_master role_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_master ALTER COLUMN role_id SET DEFAULT nextval('public.role_master_role_id_seq'::regclass);


--
-- Name: role_permissions id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_permissions ALTER COLUMN id SET DEFAULT nextval('public.role_permissions_id_seq'::regclass);


--
-- Name: table_log id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.table_log ALTER COLUMN id SET DEFAULT nextval('public.table_log_id_seq'::regclass);


--
-- Name: transaction_master_hc id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transaction_master_hc ALTER COLUMN id SET DEFAULT nextval('public.transaction_master_hc_id_seq'::regclass);


--
-- Name: user_establishments id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_establishments ALTER COLUMN id SET DEFAULT nextval('public.user_establishments_id_seq'::regclass);


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache (key, value, expiration) FROM stdin;
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: civil_court_users_master; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.civil_court_users_master (id, user_name, name, email, mobile_number, password, district_code, role_id) FROM stdin;
1	john_doe	John Doe	johndoe@example.com	9876543210	$2y$10$gnMOs5edOtGznGYfwm59UOtKUj2/2bb3lSvXBnHwqDp67F9dBoTTq	D001	2
6	john_doe1	John Doe	johndoe1@example.com	9876543211	securepassword1	D001	4
9	john_doe2	John Doe	johndoe2@example.com	9876543212	securepassword1	D001	3
\.


--
-- Data for Name: civilcourt_applicant_document_detail; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.civilcourt_applicant_document_detail (id, application_number, document_type, number_of_page, amount, file_name, upload_status, uploaded_by, uploaded_date, certified_copy_file_name, certified_copy_upload_status, certified_copy_uploaded_by, certified_copy_uploaded_date, created_at) FROM stdin;
8	KOD00315042501	other copy	1	10.00	KOD00315042501_1744712177.pdf	t	4	2025-04-15 10:16:17	KOD00315042501_1745307572.pdf	t	4	2025-04-22 07:39:32	2025-04-15 10:16:17
9	KOD00223042501	other copy	1	10.00	KOD00223042501_1745402218.pdf	t	4	2025-04-23 09:56:58	KOD00223042501_1745477374.pdf	t	4	2025-04-24 06:49:34	2025-04-23 09:56:58
10	KOD11206052501	other copy	1	5.00	KOD11206052501_1747308741.pdf	t	4	2025-05-15 17:02:21	\N	f	\N	\N	2025-05-15 17:02:21
11	RAN16522052501	order copy	24	120.00	RAN16522052501_1747984111.pdf	t	5	2025-05-23 12:38:31	\N	f	\N	\N	2025-05-23 12:38:31
12	RAN16522052501	bail copy	2	10.00	RAN16522052501_1747985020.pdf	t	5	2025-05-23 12:53:40	\N	f	\N	\N	2025-05-23 12:53:40
13	RAN19323052501	aadhar card	2	10.00	RAN19323052501_1747987434.pdf	t	5	2025-05-23 13:33:55	\N	f	\N	\N	2025-05-23 13:33:55
14	RAN21626052501	card	1	5.00	RAN21626052501_1748240349.pdf	t	5	2025-05-26 11:49:09	RAN21626052501_1748240446.pdf	t	5	2025-05-26 11:50:46	2025-05-26 11:49:09
15	RAN22826052502	MPR	2	10.00	RAN22826052502_1748240855.pdf	t	5	2025-05-26 11:57:35	RAN22826052502_1748241048.pdf	t	5	2025-05-26 12:00:48	2025-05-26 11:57:35
16	RAN22826052502	CARD	1	5.00	RAN22826052502_1748240867.pdf	t	5	2025-05-26 11:57:47	RAN22826052502_1748241054.pdf	t	5	2025-05-26 12:00:54	2025-05-26 11:57:47
17	RAN16506062501	Test Doc	3	15.00	RAN16506062501_1749196387.pdf	t	8	2025-06-06 13:23:07	RAN16506062501_1749196645.pdf	t	8	2025-06-06 13:27:25	2025-06-06 13:23:07
18	RAN16526062502	bail copy	2	10.00	RAN16526062502_1750919782.pdf	t	8	2025-06-26 12:06:22	\N	f	\N	\N	2025-06-26 12:06:22
19	RAN22726062503	dd	3	15.00	RAN22726062503_1750920166.pdf	t	8	2025-06-26 12:12:46	\N	f	\N	\N	2025-06-26 12:12:46
20	RAN21926062501	order copy	10	50.00	RAN21926062501_1750920266.pdf	t	8	2025-06-26 12:14:26	\N	f	\N	\N	2025-06-26 12:14:26
21	RAN08120062504	aadhar card	1	5.00	RAN08120062504_1750924766.pdf	t	8	2025-06-26 13:29:26	\N	f	\N	\N	2025-06-26 13:29:26
22	RAN09820062503	aadhar card	1	5.00	RAN09820062503_1750925579.pdf	t	8	2025-06-26 13:42:59	\N	f	\N	\N	2025-06-26 13:42:59
\.


--
-- Data for Name: dc_users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.dc_users (id, name, email, mobile_no, role_id, username, password, dist_code, created_at) FROM stdin;
7	Gautam	gautamkumar041996@gmail.com	9304984077	7	gautam_1	$2y$10$zmIjX2hQ0DjMndVJ2rAxYeci8qZSmqGISp5d6axU8JYaSLF9x/7aG	364	2025-06-06 11:37:57.612668
6	Tushar Anand	tusharanand303@gmail.com	9304984077	6	tushar	$2y$10$LAhSwMttdlE0q5Y5c2HefOhxuZJLVT1j.jXN5Z3fjz32iUFHwDL2O	364	2025-06-06 11:37:20.777147
8	tushar_1	tushar_1@gmail.com	9632574125	6	tushar_1	$2y$10$4LU9QRL3XF5UQculusexZ.D.5FxizdThaskalPhiFTUnwo.PFvBuS	364	2025-06-06 11:42:53.952708
9	Test Bokaro	bokaro@gmail.com	9693862938	6	bokaro_admin	$2y$10$Kku4mXxZVFSKxuWBHAXoyu7aGsZ1.EglsZ2sedJj0J62AKBDq4nmG	355	2025-06-06 13:07:01.680913
\.


--
-- Data for Name: district_application_number_tracker; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.district_application_number_tracker (id, date_key, counter, dist_code) FROM stdin;
29	2025-07-16	1	364
\.


--
-- Data for Name: district_court_applicant_registration; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.district_court_applicant_registration (application_id, application_number, district_code, establishment_code, applicant_name, mobile_number, email, case_type, case_filling_number, case_filling_year, selected_method, request_mode, required_document, applied_by, advocate_registration_number, document_status, payment_status, certified_copy_ready_status, rejection_status, rejection_remarks, rejected_by, rejection_date, created_by, updated_by, created_at, updated_at) FROM stdin;
43	RAN22816072501	364	JHRN01	Tushar Anand	9304984077	tusharanand303@gmail.com	228	121	2020	F	ordinary	Testing Document	respondent	\N	0	3	0	0	\N	0	\N	system	\N	2025-07-16 17:57:50.038709	2025-07-16 17:57:50.038709
\.


--
-- Data for Name: district_court_case_master; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.district_court_case_master (case_type, type_name, est_code) FROM stdin;
218	Bail Petition : Bail Petition	JHBK03
185	B.f. : B.F. Case	JHBK03
198	B.f. : B.F. Case	JHBK03
187	B.o.c.w. : B.O.C.W.	JHBK03
200	B.s.e : B.S.E	JHBK03
225	C1(cp) : C1(CP)	JHBK03
227	C1(dva) : C1(DVA)	JHBK03
226	C1(n.i Act) : C1(N.I ACT)	JHBK03
250	C2 : C2	JHBK03
234	C2(cla) : C2(CLA)	JHBK03
236	C2(co) : C2(CO)	JHBK03
241	C2(ele) : C2(ELE)	JHBK03
232	C2(epf) : C2(EPF)	JHBK03
237	C2(era) : C2(ERA)	JHBK03
238	C2(fa) : C2(FA)	JHBK03
230	C2(fssa) : C2(FSSA)	JHBK03
242	C2(id) : C2(ID)	JHBK03
249	C2(leo) : C2(LEO)	JHBK03
235	C2(lma) : C2(LMA)	JHBK03
229	C2(mv) : C2(MV)	JHBK03
239	C2(mw) : C2(MW)	JHBK03
231	C2(n.fir) : C2(N.FIR)	JHBK03
240	C2(pfa) : C2(PFA)	JHBK03
245	C2(pw) : C2(PW)	JHBK03
233	C2(rly) : C2(RLY)	JHBK03
246	C2(spca) : C2(SPCA)	JHBK03
243	C2(wm) : C2(WM)	JHBK03
244	C3(ce) : C3(CE)	JHBK03
247	C3(excise) : C3(EXCISE)	JHBK03
248	C3(forest) : C3(FOREST)	JHBK03
204	Child Labour Act : Child Labour Act	JHBK03
203	C.l.a. : C.L.A. Cases	JHBK03
251	Complaint Case : Complaint Case	JHBK03
159	Cr. Case Complaint (O) : Cr. Case Complaint (	JHBK03
142	Cr. Case Complaint (P) : Cr. Case Complaint (	JHBK03
196	Cri. Misc. Case : Criminal Misc. Case	JHBK03
147	Domestic Violence Act 2005 : Domestic Violence Ac	JHBK03
253	E. C. Cases : E. C. Cases	JHBK03
211	E/e : Employment Exchange	JHBK03
191	E.p.f. : Employee Providend F	JHBK03
190	E.r.a. : Equal Remuneration A	JHBK03
206	Era : Euqal Remunoration A	JHBK03
149	Excise Act : Excise Act	JHBK03
193	F . A . : Factory Act Case	JHBK03
222	Food Safety And Standards Act : FOOD SAFETY AND STAN	JHBK03
180	G. R. Cases : G. R. Cases	JHBK03
209	I. D : Industrial Dispute A	JHBK03
257	Interlocutary Application : Interlocutary Application	JHBK03
258	Misc. Cri.  Application : Misc. Criminal Application	JHBK03
212	Misc. Petition : Misc. Petition	JHBK03
210	M. V : Motor Vehicle	JHBK03
189	M.w. : Minimum Wages Case	JHBK03
199	Nfir : NON FIR	JHBK03
195	P. F. A. : Prev. Food Adult. Ac	JHBK03
153	Regular Bail : Regular Bail	JHBK03
192	S.p.c.a. : S . P.  C . A .	JHBK03
148	Weight and Measurement Act : Weight and Measureme	JHBK03
160	Weight &amp; measurement Act 1985 : Weight &amp; measurement	JHBK03
23	Arbitration Case : Arbitration Case	JHBK04
197	Civil Misc. Case : Civil Misc. Case	JHBK04
90	DISSOLUTION OF PATNERSHIP : DISSOLUTION OF PATNE	JHBK04
112	Execution Cases : EXECUTION CASES	JHBK04
33	FINAL DECREE : FINAL DECREE	JHBK04
213	Final Decree Title Suit : Final Decree Title S	JHBK04
161	GUARDIANSHIP : GUARDIANSHIP	JHBK04
253	Interlocutary Application : Interlocutary Application	JHBK04
251	Land Acquisition Cas : Land Acquisition Cas	JHBK04
109	La  Ref. : LAND ACQUISITION  RE	JHBK04
105	MISC CASES a) O-9 , R-4  b) O-9, R-9 c) U/S 47CPC : MISC CASES a) O-9 ,	JHBK04
254	Misc. Civil Application : Misc. Civil Application	JHBK04
212	Misc. Petition : Misc. Petition	JHBK04
158	Mislaneous : Mislaneous	JHBK04
103	M. S : MONEY SUIT	JHBK04
250	Original Suit : Original Suit	JHBK04
120	RESTORATION OF SUITS 0-9 R-4 , 0-9 , R -9 : RESTORATION OF SUITS	JHBK04
201	T(arb) S : Title Aribitaion Sui	JHBK04
155	T. Ev. Suit : Title Eviction Suit	JHBK04
183	Title(declaration) Suit : Title(Declaration) S	JHBK04
154	T. Mortgage Suit : Title Mortgage Suit	JHBK04
181	T.p.s : Title(Partition)Suit	JHBK04
214	T P S Fd : Final Decree Title P	JHBK04
143	T.s : Title Suit	JHBK04
152	Antic.Bail Petition : Anticipatory Bail Petition	JHBT04
153	Bail Petition : Bail Petition	JHBT04
175	C.1 : C.1	JHBT04
196	C/1.s : C/1.s	JHBT04
177	C.3 : C.3	JHBT04
197	C/3.s : C/3.s	JHBT04
178	C.7 : C.7	JHBT04
210	Children Case : Children Case	JHBT04
1	CIVIL APPEAL : CIVIL APPEAL	JHBT04
166	Civil Misc.Appeal : CIVIL MISC.APPEAL	JHBT04
195	Complaint Case : Complaint Case	JHBT04
163	Confiscation Appeal : Confiscation Appeal	JHBT04
12	Criminal Appeal : CRIMINAL APPEAL	JHBT04
164	Criminal Misc. : Criminal Misc.	JHBT04
13	Criminal Revision : Criminal Revision	JHBT04
205	E.C. Cases : E.C. Cases	JHBT04
184	Election Petition : ELECTION PETITION	JHBT04
211	Electricity Act Cases : Electricity Act Cases	JHBT04
168	Eviction Appeal : EVICTION APPEAL	JHBT04
112	Execution Case : EXECUTION CASE	JHBT04
162	G.R.case : G.R.case	JHBT04
190	G.R.s : G.R. suplimentary	JHBT04
193	G.R.s1 : G.R.s1	JHBT04
194	G.R.s2 : G.R.s2	JHBT04
161	GUARDIANSHIP CASE : GUARDIANSHIP CASE	JHBT04
109	LAND ACQUI. CASE : LAND ACQUI. CASE	JHBT04
146	Letter of Admin.Case : Letter of  Administration Case	JHBT04
173	Misc.Case : Misc.Case	JHBT04
41	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHBT04
40	MISC Cri. APPLICATION : MISC Cri. APPLICATION	JHBT04
187	Misc (p). Case : Misc.Petition Case	JHBT04
169	Mot.Acci.Claims Case : Motor Accident Claims Case	JHBT04
188	N.D.P.S. Case : N.D.P.S. Case	JHBT04
203	NDPS.s : NDPS Suppl	JHBT04
207	NDPSs1 : NDPSs1	JHBT04
143	Original Suit : ORIGINAL SUIT	JHBT04
108	PROBATE CASE : PROBATE CASE	JHBT04
179	R.A.case : R.A.case	JHBT04
201	Revocation Case : Revocation Case	JHBT04
192	SC-ST Case : SC-ST Case	JHBT04
202	SC-ST.s : SC-ST.s case	JHBT04
10	Sessions Trial : SESSIONS TRIAL	JHBT04
189	Spl POCSO Case : Spl POCSO Case	JHBT04
204	Spl POCSOs : Spl POCSOs	JHBT04
191	S.T.s : S.T. supplimentary	JHBT04
198	S.T.s1 : S.T.s1	JHBT04
209	S.T.s2 : S.T.s2	JHBT04
111	Succession Cert.Case : SUCCESSION CERTIFICATE CASES	JHBT04
167	Title. P.Appeal : Title. P. Appeal	JHBT04
172	Title. P. Suit : TITLE P. SUIT	JHBT04
208	Transfer Petition : Transfer Petition	JHBT04
183	Vigilance Case : Vigilance Case	JHBT04
206	Vigilance Case(S) : Vigilance Case(S)	JHBT04
23	ARBITRATION CASE : ARBITRATION CASE	JHBK02
197	Civil Misc. Case : Civil Misc. Case	JHBK02
259	Commercial Arbitration : Commercial Arbitration	JHBK02
260	Commercial Civil Misc. Case : Commercial Civil Misc. Case	JHBK02
258	Commercial Execution : Commercial Execution	JHBK02
257	Commercial Suit : Commercial Suit	JHBK02
90	DISSOLUTION OF PATNERSHIP : DISSOLUTION OF PATNE	JHBK02
112	Execution Case : EXECUTION CASE	JHBK02
33	FINAL DECREE : FINAL DECREE	JHBK02
213	Final Decree Title Suit : Final Decree Title S	JHBK02
146	L.a  Exec : L.A EXECUTION CASE	JHBK02
252	L. A. Execution Case : Land Acquisition Exe	JHBK02
253	Land Acq. Misc. Case : Land Acquisitive Mis	JHBK02
251	Land Acquisition Case : Land Acquisition Case	JHBK02
5	Land Acqusition Misc. Case : Land Acqusition Misc. Case	JHBK02
109	La  Ref. : LAND ACQUISITION  RE	JHBK02
202	Misc (arb) S : Misc (Arb) Suits	JHBK02
256	Misc. Civil Application : Misc. Civil Application	JHBK02
212	Misc. Petition : Misc. Petition	JHBK02
158	Mislaneous : Mislaneous	JHBK02
103	M. S : MONEY SUIT	JHBK02
250	Original Suit : Original Suit	JHBK02
201	T(arb) S : Title Aribitaion Sui	JHBK02
219	T. Damage Suit : Title Damage Suit	JHBK02
155	T. Ev. Suit : Title Eviction Suit	JHBK02
183	Title(declaration) Suit : Title(Declaration) S	JHBK02
154	T. Mortgage Suit : Title Mortgage Suit	JHBK02
181	T.p.s : Title(Partition)Suit	JHBK02
214	T P S Fd : Final Decree Title P	JHBK02
143	T.s : Title Suit	JHBK02
152	Antic.Bail Petition : Anticipatory Bail Petition	JHBT02
23	ARBITRATION CASE : ARBITRATION CASE	JHBT02
153	Bail Petition : Bail Petition	JHBT02
175	C.1 : C.1	JHBT02
196	C/1.s : C/1.s	JHBT02
177	C.3 : C.3	JHBT02
197	C/3.s : C/3.s	JHBT02
178	C.7 : C.7	JHBT02
210	Children Case : Children Case	JHBT02
1	CIVIL APPEAL : CIVIL APPEAL	JHBT02
166	Civil Misc.Appeal : CIVIL MISC.APPEAL	JHBT02
195	Complaint Case : Complaint Case	JHBT02
163	Confiscation Appeal : Confiscation Appeal	JHBT02
12	Criminal Appeal : CRIMINAL APPEAL	JHBT02
164	Criminal Misc. : Criminal Misc.	JHBT02
13	Criminal Revision : Criminal Revision	JHBT02
205	E.C. Cases : E.C. Cases	JHBT02
184	Election Petition : ELECTION PETITION	JHBT02
211	Electricity Act Cases : Electricity Act Cases	JHBT02
168	Eviction Appeal : EVICTION APPEAL	JHBT02
112	Execution Case : EXECUTION CASE	JHBT02
162	G.R.case : G.R.case	JHBT02
190	G.R.s : G.R. suplimentary	JHBT02
193	G.R.s1 : G.R.s1	JHBT02
194	G.R.s2 : G.R.s2	JHBT02
161	GUARDIANSHIP CASE : GUARDIANSHIP CASE	JHBT02
109	LAND ACQUI. CASE : LAND ACQUI. CASE	JHBT02
146	Letter of Admin.Case : Letter of  Administration Case	JHBT02
173	Misc.Case : Misc.Case	JHBT02
41	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHBT02
40	MISC Cri. APPLICATION : MISC Cri. APPLICATION	JHBT02
187	Misc (p). Case : Misc.Petition Case	JHBT02
169	Mot.Acci.Claims Case : Motor Accident Claims Case	JHBT02
188	N.D.P.S. Case : N.D.P.S. Case	JHBT02
203	NDPS.s : NDPS Suppl	JHBT02
207	NDPSs1 : NDPSs1	JHBT02
143	Original Suit : ORIGINAL SUIT	JHBT02
108	PROBATE CASE : PROBATE CASE	JHBT02
179	R.A.case : R.A.case	JHBT02
201	Revocation Case : Revocation Case	JHBT02
192	SC-ST Case : SC-ST Case	JHBT02
202	SC-ST.s : SC-ST.s case	JHBT02
10	Sessions Trial : SESSIONS TRIAL	JHBT02
189	Spl POCSO Case : Spl POCSO Case	JHBT02
204	Spl POCSOs : Spl POCSOs	JHBT02
191	S.T.s : S.T. supplimentary	JHBT02
198	S.T.s1 : S.T.s1	JHBT02
209	S.T.s2 : S.T.s2	JHBT02
111	Succession Cert.Case : SUCCESSION CERTIFICATE CASES	JHBT02
167	Title. P.Appeal : Title. P. Appeal	JHBT02
172	Title. P. Suit : TITLE P. SUIT	JHBT02
208	Transfer Petition : Transfer Petition	JHBT02
183	Vigilance Case : Vigilance Case	JHBT02
206	Vigilance Case(S) : Vigilance Case(S)	JHBT02
152	Antic.Bail Petition : Anticipatory Bail Petition	JHBT01
153	Bail Petition : Bail Petition	JHBT01
175	C.1 : C.1	JHBT01
196	C/1.s : C/1.s	JHBT01
177	C.3 : C.3	JHBT01
197	C/3.s : C/3.s	JHBT01
178	C.7 : C.7	JHBT01
210	Children Case : Children Case	JHBT01
1	CIVIL APPEAL : CIVIL APPEAL	JHBT01
166	Civil Misc.Appeal : CIVIL MISC.APPEAL	JHBT01
195	Complaint Case : Complaint Case	JHBT01
163	Confiscation Appeal : Confiscation Appeal	JHBT01
12	Criminal Appeal : CRIMINAL APPEAL	JHBT01
164	Criminal Misc. : Criminal Misc.	JHBT01
13	Criminal Revision : Criminal Revision	JHBT01
205	E.C. Cases : E.C. Cases	JHBT01
184	Election Petition : ELECTION PETITION	JHBT01
211	Electricity Act Cases : Electricity Act Cases	JHBT01
168	Eviction Appeal : EVICTION APPEAL	JHBT01
112	Execution Case : EXECUTION CASE	JHBT01
162	G.R.case : G.R.case	JHBT01
190	G.R.s : G.R. suplimentary	JHBT01
193	G.R.s1 : G.R.s1	JHBT01
194	G.R.s2 : G.R.s2	JHBT01
161	GUARDIANSHIP CASE : GUARDIANSHIP CASE	JHBT01
109	LAND ACQUI. CASE : LAND ACQUI. CASE	JHBT01
146	Letter of Admin.Case : Letter of  Administration Case	JHBT01
173	Misc.Case : Misc.Case	JHBT01
41	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHBT01
40	MISC Cri. APPLICATION : MISC Cri. APPLICATION	JHBT01
187	Misc (p). Case : Misc.Petition Case	JHBT01
169	Mot.Acci.Claims Case : Motor Accident Claims Case	JHBT01
188	N.D.P.S. Case : N.D.P.S. Case	JHBT01
203	NDPS.s : NDPS Suppl	JHBT01
207	NDPSs1 : NDPSs1	JHBT01
143	Original Suit : ORIGINAL SUIT	JHBT01
108	PROBATE CASE : PROBATE CASE	JHBT01
179	R.A.case : R.A.case	JHBT01
201	Revocation Case : Revocation Case	JHBT01
192	SC-ST Case : SC-ST Case	JHBT01
202	SC-ST.s : SC-ST.s case	JHBT01
10	Sessions Trial : SESSIONS TRIAL	JHBT01
189	Spl POCSO Case : Spl POCSO Case	JHBT01
204	Spl POCSOs : Spl POCSOs	JHBT01
191	S.T.s : S.T. supplimentary	JHBT01
198	S.T.s1 : S.T.s1	JHBT01
209	S.T.s2 : S.T.s2	JHBT01
111	Succession Cert.Case : SUCCESSION CERTIFICATE CASES	JHBT01
167	Title. P.Appeal : Title. P. Appeal	JHBT01
172	Title. P. Suit : TITLE P. SUIT	JHBT01
208	Transfer Petition : Transfer Petition	JHBT01
183	Vigilance Case : Vigilance Case	JHBT01
206	Vigilance Case(S) : Vigilance Case(S)	JHBT01
205	Adoptation : Adoptation	JHBK05
207	Adoptation : Adoptation	JHBK05
197	Civil Misc. Case : Civil Misc. Case	JHBK05
196	Cri. Misc. Case : Criminal Misc. Case	JHBK05
112	Execution Cases : EXECUTION CASES	JHBK05
161	GUARDIANSHIP : GUARDIANSHIP	JHBK05
255	Interlocutary Application : Interlocutary Application	JHBK05
156	Maintainance : Maintainance	JHBK05
251	MaintenanceAlterationCase : Maintenance Alterati	JHBK05
208	Maintenance Suit : Maintenance suit	JHBK05
256	Misc. Civil Application : Misc. Civil Application	JHBK05
252	OriginalMaintenanceCase : Original Maintenance	JHBK05
250	Original Suit : Original Suit	JHBK05
179	Title(mat.) Suit : Title (Matrimonial)	JHBK05
152	Antic.Bail Petition : Anticipatory Bail Petition	JHBT05
153	Bail Petition : Bail Petition	JHBT05
175	C.1 : C.1	JHBT05
196	C/1.s : C/1.s	JHBT05
177	C.3 : C.3	JHBT05
197	C/3.s : C/3.s	JHBT05
178	C.7 : C.7	JHBT05
210	Children Case : Children Case	JHBT05
1	CIVIL APPEAL : CIVIL APPEAL	JHBT05
166	Civil Misc.Appeal : CIVIL MISC.APPEAL	JHBT05
214	Civil Misc Case : Civil Misc Case	JHBT05
195	Complaint Case : Complaint Case	JHBT05
163	Confiscation Appeal : Confiscation Appeal	JHBT05
12	Criminal Appeal : CRIMINAL APPEAL	JHBT05
164	Criminal Misc. : Criminal Misc.	JHBT05
13	Criminal Revision : Criminal Revision	JHBT05
205	E.C. Cases : E.C. Cases	JHBT05
184	Election Petition : ELECTION PETITION	JHBT05
211	Electricity Act Cases : Electricity Act Cases	JHBT05
168	Eviction Appeal : EVICTION APPEAL	JHBT05
112	Execution Case : EXECUTION CASE	JHBT05
162	G.R.case : G.R.case	JHBT05
190	G.R.s : G.R. suplimentary	JHBT05
193	G.R.s1 : G.R.s1	JHBT05
194	G.R.s2 : G.R.s2	JHBT05
161	GUARDIANSHIP CASE : GUARDIANSHIP CASE	JHBT05
109	LAND ACQUI. CASE : LAND ACQUI. CASE	JHBT05
146	Letter of Admin.Case : Letter of  Administration Case	JHBT05
213	MA : Maintenance Alteration Case	JHBT05
173	Misc.Case : Misc.Case	JHBT05
41	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHBT05
40	MISC Cri. APPLICATION : MISC Cri. APPLICATION	JHBT05
187	Misc (p). Case : Misc.Petition Case	JHBT05
169	Mot.Acci.Claims Case : Motor Accident Claims Case	JHBT05
188	N.D.P.S. Case : N.D.P.S. Case	JHBT05
203	NDPS.s : NDPS Suppl	JHBT05
207	NDPSs1 : NDPSs1	JHBT05
212	OMC : Original Maintenance Case	JHBT05
143	Original Suit : ORIGINAL SUIT	JHBT05
108	PROBATE CASE : PROBATE CASE	JHBT05
179	R.A.case : R.A.case	JHBT05
201	Revocation Case : Revocation Case	JHBT05
192	SC-ST Case : SC-ST Case	JHBT05
202	SC-ST.s : SC-ST.s case	JHBT05
10	Sessions Trial : SESSIONS TRIAL	JHBT05
189	Spl POCSO Case : Spl POCSO Case	JHBT05
204	Spl POCSOs : Spl POCSOs	JHBT05
191	S.T.s : S.T. supplimentary	JHBT05
198	S.T.s1 : S.T.s1	JHBT05
209	S.T.s2 : S.T.s2	JHBT05
111	Succession Cert.Case : SUCCESSION CERTIFICATE CASES	JHBT05
167	Title. P.Appeal : Title. P. Appeal	JHBT05
172	Title. P. Suit : TITLE P. SUIT	JHBT05
208	Transfer Petition : Transfer Petition	JHBT05
183	Vigilance Case : Vigilance Case	JHBT05
206	Vigilance Case(S) : Vigilance Case(S)	JHBT05
152	Antic.Bail Petition : Anticipatory Bail Petition	JHBT03
153	Bail Petition : Bail Petition	JHBT03
175	C.1 : C.1	JHBT03
196	C/1.s : C/1.s	JHBT03
177	C.3 : C.3	JHBT03
197	C/3.s : C/3.s	JHBT03
178	C.7 : C.7	JHBT03
210	Children Case : Children Case	JHBT03
1	CIVIL APPEAL : CIVIL APPEAL	JHBT03
166	Civil Misc.Appeal : CIVIL MISC.APPEAL	JHBT03
195	Complaint Case : Complaint Case	JHBT03
163	Confiscation Appeal : Confiscation Appeal	JHBT03
12	Criminal Appeal : CRIMINAL APPEAL	JHBT03
164	Criminal Misc. : Criminal Misc.	JHBT03
13	Criminal Revision : Criminal Revision	JHBT03
205	E.C. Cases : E.C. Cases	JHBT03
184	Election Petition : ELECTION PETITION	JHBT03
211	Electricity Act Cases : Electricity Act Cases	JHBT03
168	Eviction Appeal : EVICTION APPEAL	JHBT03
112	Execution Case : EXECUTION CASE	JHBT03
162	G.R.case : G.R.case	JHBT03
190	G.R.s : G.R. suplimentary	JHBT03
193	G.R.s1 : G.R.s1	JHBT03
194	G.R.s2 : G.R.s2	JHBT03
161	GUARDIANSHIP CASE : GUARDIANSHIP CASE	JHBT03
109	LAND ACQUI. CASE : LAND ACQUI. CASE	JHBT03
146	Letter of Admin.Case : Letter of  Administration Case	JHBT03
173	Misc.Case : Misc.Case	JHBT03
41	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHBT03
40	MISC Cri. APPLICATION : MISC Cri. APPLICATION	JHBT03
187	Misc (p). Case : Misc.Petition Case	JHBT03
169	Mot.Acci.Claims Case : Motor Accident Claims Case	JHBT03
188	N.D.P.S. Case : N.D.P.S. Case	JHBT03
203	NDPS.s : NDPS Suppl	JHBT03
207	NDPSs1 : NDPSs1	JHBT03
143	Original Suit : ORIGINAL SUIT	JHBT03
108	PROBATE CASE : PROBATE CASE	JHBT03
179	R.A.case : R.A.case	JHBT03
201	Revocation Case : Revocation Case	JHBT03
192	SC-ST Case : SC-ST Case	JHBT03
202	SC-ST.s : SC-ST.s case	JHBT03
10	Sessions Trial : SESSIONS TRIAL	JHBT03
189	Spl POCSO Case : Spl POCSO Case	JHBT03
204	Spl POCSOs : Spl POCSOs	JHBT03
191	S.T.s : S.T. supplimentary	JHBT03
198	S.T.s1 : S.T.s1	JHBT03
209	S.T.s2 : S.T.s2	JHBT03
111	Succession Cert.Case : SUCCESSION CERTIFICATE CASES	JHBT03
167	Title. P.Appeal : Title. P. Appeal	JHBT03
172	Title. P. Suit : TITLE P. SUIT	JHBT03
208	Transfer Petition : Transfer Petition	JHBT03
183	Vigilance Case : Vigilance Case	JHBT03
206	Vigilance Case(S) : Vigilance Case(S)	JHBT03
152	Anticipatory Bail Petition : Anticipatory Bail Petition	JHBK01
23	ARBITRATION CASE : ARBITRATION CASE	JHBK01
218	Bail Petition : Bail Petition	JHBK01
271	Children Case : Children Case	JHBK01
262	CIVIL APPEAL : CIVIL APPEAL	JHBK01
2	CIVIL MISC. APPEAL : CIVIL MISC. APPEAL	JHBK01
197	Civil Misc. Case : Civil Misc. Case	JHBK01
266	CIVIL REVISION : CIVIL REVISION	JHBK01
273	Commercial Appeal : Commercial Appeal	JHBK01
277	Commercial Arbitration : Commercial Arbitration	JHBK01
276	Commercial Execution : Commercial Execution	JHBK01
274	Commercial Misc. Appeal : Commercial Misc. Appeal	JHBK01
272	Commercial Suit : Commercial Suit	JHBK01
267	Complaint Case : Complaint Case	JHBK01
216	Confiscation Appeal : Confiscation Appeal	JHBK01
142	Cr. Case Complaint (P) : Cr. Case Complaint (	JHBK01
12	CRIMINAL APPEAL : CRIMINAL APPEAL	JHBK01
157	Criminal Misc. : Criminal Misc.	JHBK01
178	Criminal Revision. : Criminal Revision.	JHBK01
196	Cri. Misc. Case : Criminal Misc. Case	JHBK01
256	Electricity Act Case : Electricity Act Case	JHBK01
112	Execution Cases : EXECUTION CASES	JHBK01
180	G. R. : G. R.	JHBK01
161	GUARDIANSHIP CASE : GUARDIANSHIP CASE	JHBK01
263	Insolvency Case : Insolvency Case	JHBK01
268	Interlocutary Application : Interlocutary Application	JHBK01
275	Land Acquisition Case : Land Acquisition Case	JHBK01
221	Letter Of Administration Case : Letter Of Administra	JHBK01
270	Misc. Civil Application : Misc. Civil Application	JHBK01
269	Misc. Criminal Application : Misc. Criminal Application	JHBK01
140	MONEY APPEAL : MONEY APPEAL	JHBK01
251	Motor Accident Claim Cases : Motor Accident Claim Case	JHBK01
106	Mv Claim : Motar Vechile CLAIM	JHBK01
163	NDPS : NDPS CASE	JHBK01
252	Original Suit : Original Suit	JHBK01
108	Probate : PROBATE CASE	JHBK01
264	Revocation Case : Revocation Case	JHBK01
184	SC/ST : SC/ST	JHBK01
255	Sessions Trial : Sessions Trial	JHBK01
223	Special Pocso : Prot Of Child Sexual	JHBK01
182	S. T : Session Trial	JHBK01
111	Succession Certificate Case : Succession Certifica	JHBK01
220	Testramonetary Suit : Testramonetary Suit	JHBK01
1	TITLE APPEAL : TITLE APPEAL	JHBK01
143	Title Suit : Title Suit	JHBK01
265	Transfer Petition : Transfer Petition	JHBK01
258	Vigilance Case : Vigilance Case	JHBK01
89	ADMINISTRATIVE SUITE : ADMINISTRATIVE SUITE	JHDM02
138	APP. OF RES. SUBJUDICE U/S 10 : APP. OF RES. SUBJUDICE U/S 10	JHDM02
23	ARBITRATION CASE : ARBITRATION CASE	JHDM02
173	Civil Misc : Civil Misc	JHDM02
158	Civil Misc. Case : Civil Misc. Case	JHDM02
48	CONTEMPT PROCEEDINGS : CONTEMPT PROCEEDINGS	JHDM02
98	CROSS SUIT : CROSS SUIT	JHDM02
8	ELECT. PETN : ELECT. PETN	JHDM02
112	Execution Case : Execution Case	JHDM02
33	FINAL DECREE : FINAL DECREE	JHDM02
135	INJUNCTION O-39 , R1, 2 : INJUNCTION O-39 , R1, 2	JHDM02
35	INSOLVENCY : INSOLVENCY	JHDM02
146	L.A : L.A	JHDM02
109	Land Acquisition Case : Land Acquisition Case	JHDM02
183	L.a.r. : L.A.R.	JHDM02
38	MESNE PROFIT : MESNE PROFIT	JHDM02
164	Misc : MISC	JHDM02
194	Misc. Civil Application : Misc. Civil Application	JHDM02
103	MONEY SUIT : MONEY SUIT	JHDM02
193	Original Suit : Original Suit	JHDM02
45	PAUPER APPLICATION : PAUPER APPLICATION	JHDM02
17	REG. CIVIL SUIT : REG. CIVIL SUIT	JHDM02
42	RENT SUIT : RENT SUIT	JHDM02
120	RESTORATION OF SUITS 0-9 R-4 , 0-9 , R -9 : RESTORATION OF SUITS 0-9 R-4 ,	JHDM02
121	SETTING  ASIDE EXPARTE ORDER 0-9 - R -13 : SETTING  ASIDE EXPARTE ORDER 0	JHDM02
41	SMALL CAUSE SUIT : SMALL CAUSE SUIT	JHDM02
19	SPL.CIV. SUIT : SPL.CIV. SUIT	JHDM02
91	SUITE BY GOVT OR PUBLIC  OFFICERS IN OFFICIAL CAPA : SUITE BY GOVT OR PUBLIC  OFFIC	JHDM02
155	Title Eviction Suit : Title Eviction Suit	JHDM02
168	Title Excution Apeal : Title Excution Apeal	JHDM02
154	Title Mortgage Suit : Title Mortgage Suit	JHDM02
167	Title (partition) Suit : Title (Partition) Suit	JHDM02
143	Title Suit : Title Suit	JHDM02
102	TITLE SUIT/T.P. SUIT : TITLE SUIT/TITLE PARTITION SUI	JHDM02
152	Anticipatory Bail Petition : Anticipatory Bail Petition	JHDM01
23	ARBITRATION CASE : ARBITRATION CASE	JHDM01
188	Bail Petition : Bail Petition (B.P)	JHDM01
222	Children Case : Children Case	JHDM01
199	Civil Appeal : Civil Appeal	JHDM01
173	Civil Misc : Civil Misc	JHDM01
2	Civil Misc. Appeal : Civil Misc. Appeal	JHDM01
226	Commercial Appeal : Commercial Appeal	JHDM01
225	Commercial Suit : Commercial Suit	JHDM01
142	COMPLAINT CASE : COMPLAINT CASE	JHDM01
9	Cri. Apl : Cri. Apl	JHDM01
12	Criminal Appeal : Criminal Appeal	JHDM01
163	Criminal Misc. : Criminal Misc.	JHDM01
13	Criminal Revision : Criminal Revision	JHDM01
14	CRI. Misc. Appeal : CRI. Misc. Appeal	JHDM01
221	Cyber Case : Cyber Case	JHDM01
227	Drugs and Cosmetics : Drugs and Cosmetics	JHDM01
180	Electricity Act Cases : Electricity Act cases	JHDM01
16	Electricity Spl. Casee : Electricity spl. casee	JHDM01
112	EXECUITION CASES : EXECUITION CASES	JHDM01
33	FINAL DECREE : FINAL DECREE	JHDM01
162	G.R. Cases : G.R	JHDM01
161	Guardianship  Case : Guardianship  Case	JHDM01
35	Insolvency Case : Insolvency Case	JHDM01
146	Land Acquisition : Land Acquisition	JHDM01
109	Land Acquisition Appeal : Land Acquisition Appeal	JHDM01
5	LAND REF. : LAND REF.	JHDM01
214	Letter of Administration Case : Letter of Administration Case	JHDM01
158	Misc. Case : Misc. Case	JHDM01
224	Misc. Civil Application : Misc. Civil Application	JHDM01
223	Misc. Cri. Application : Misc. Cri. Application	JHDM01
106	Motor Accident Claims Casess : Motor Accident Claims Casess	JHDM01
15	NDPS Spl Case : NDPS. S. CASE	JHDM01
202	NDPS Spl Case A : NDPS. S. CASE A	JHDM01
179	O.C.R : O.C.R.	JHDM01
204	Original Suit : Original Suit	JHDM01
176	P.C.R : P.C.R	JHDM01
213	Pocso Act Case A : Pocso Act Case A	JHDM01
108	PROBATE CASE : PROBATE CASE	JHDM01
153	Regular Bail : Regular Bail	JHDM01
207	Revocation Case : Revocation Case	JHDM01
194	SC and ST Case : SC and ST Case	JHDM01
10	Sessions Trial : Sessions Trial	JHDM01
209	Session Trial A : Session Trial A	JHDM01
195	Special Pocso Case : Special Pocso Case	JHDM01
111	Succession Certificate Case : Succession Certificate Case	JHDM01
1	TITLE APPEAL : TITLE APPEAL	JHDM01
184	Title (arbitration) Suit : Title (Arbitration) Suit	JHDM01
181	Title Claim Suit : Title Claim Suit	JHDM01
155	Title Eviction Suit : Title Eviction Suit	JHDM01
154	Title Mortgage Suit : Title Mortgage Suit	JHDM01
165	Title (partition) Apeal : Title (Partition) Apeal	JHDM01
167	Title (partition) Suit : Title (Partition) Suit	JHDM01
192	Transfer Petition : Transfer Petition	JHDM01
178	Vigilance Case : Vigilance Case	JHDM01
205	Vigilance Case A : Vigilance Case A	JHDM01
206	Vigilance Case B : Vigilance Case B	JHDM01
173	Civil Misc. Case : Civil Misc. Case	JHDM05
170	Criminal Execution : Cri. Exeu	JHDM05
112	EXECUITION CASES : EXECUITION CASES	JHDM05
161	GUARDIANSHIP : GUARDIANSHIP	JHDM05
193	Maintenance Alteration case : Maintenance Alteration case	JHDM05
197	Misc. Civil Application : Misc. Civil Application	JHDM05
196	Misc. Cri. Application : Misc. Cri. Application	JHDM05
195	Misc. F : Misc. F	JHDM05
194	Misc. M : Misc. M	JHDM05
156	Original Maintenance Case : Original Maintenance Case	JHDM05
107	Original Suit : Original Suit	JHDM05
188	Bail Petition (b.p) : Bail Petition (B.P)	JHDM03
142	Complaint Case : Complaint Case	JHDM03
159	Cr. Case Complaint O : Cr. Case Complaint O	JHDM03
24	CRI. BAIL APPLN. : CRI. BAIL APPLN.	JHDM03
21	CRI. CASE : CRI. CASE	JHDM03
170	Cri. Exeuction Case : Cri. Exeuction Case	JHDM03
163	Cri. Misc : Cri. Misc	JHDM03
80	DISTRESS WARRENT : DISTRESS WARRENT	JHDM03
147	Domestic Violence Act 2005 : Domestic Violence Act 2005	JHDM03
32	E.S.I. ACT  CASE : E.S.I. ACT  CASE	JHDM03
149	Excise Act : Excise Act	JHDM03
162	G.R. Cases : G.R. Cases	JHDM03
193	G.R. S : Split G.R. A	JHDM03
196	G.R. S 1 : G.R. S 1	JHDM03
197	G.R. S 2 : G.R. S 2	JHDM03
200	Juvenile Cases : Juvenile Cases	JHDM03
38	MESNE PROFIT : MESNE PROFIT	JHDM03
199	Misc. Cri. Application : Misc. Cri. Application	JHDM03
179	O.C.R. : O.C.R.	JHDM03
30	OTHER MISC. CRI. APPLN. : OTHER MISC. CRI. APPLN.	JHDM03
176	P.C.R : P.C.R	JHDM03
20	REG. CRI. CASE : REG. CRI. CASE	JHDM03
153	Regular Bail : Regular Bail	JHDM03
31	REVIEW APPLICATION : REVIEW APPLICATION	JHDM03
25	SPL. CRI. MA : SPL. CRI. MA	JHDM03
148	Weight and Measurement Act : Weight and Measurement Act	JHDM03
160	Weight and measurement Act 1985 : Weight and measurement Act1985	JHDM03
165	BAIL PETITION : BAIL PETITION	JHKD03
185	B.O.C Act : B.O.C ACT	JHKD03
173	Complaint Case : Complaint Case	JHKD03
159	Cr. Case Complaint (O) : Cr. Case Complaint (O)	JHKD03
142	Cr. Case Complaint (P) : Cr. Case Complaint (P)	JHKD03
168	Cr. execution.case : Cr.Execution Case	JHKD03
24	CRI. BAIL APPLN. : CRI. BAIL APPLN.	JHKD03
21	CRI. CASE : CRI. CASE	JHKD03
157	Cri. Misc. Case : Criminal Misc. CASE	JHKD03
80	DISTRESS WARRENT : DISTRESS WARRENT	JHKD03
147	Domestic Violence Act 2005 : Domestic Violence Act 2005	JHKD03
192	Employement Exchange : Employement Exchange	JHKD03
32	E.S.I. ACT  CASE : E.S.I. ACT  CASE	JHKD03
149	Excise Act : Excise Act	JHKD03
176	F.a.c. : Forest Act Cases	JHKD03
177	Factory Act : Factory Act	JHKD03
201	G.cases : G.CASES	JHKD03
164	G.R. : General Register No.	JHKD03
213	Juvenile Cases : Juvenile Cases	JHKD03
211	Misc Cri. Application : Misc Cri. Application	JHKD03
184	Misc Petition : Misc Petition	JHKD03
198	Non F I R : Non F I R	JHKD03
30	OTHER MISC. CRI. APPLN. : OTHER MISC. CRI. APPLN.	JHKD03
20	REG. CRI. CASE : REG. CRI. CASE	JHKD03
153	Regular Bail : Regular Bail	JHKD03
31	REVIEW APPLICATION : REVIEW APPLICATION	JHKD03
25	SPL. CRI. MA : SPL. CRI. MA	JHKD03
182	Under Railway Act : Under Railway Act	JHKD03
193	Vigilance P.S Case : Vigilance P.S Case	JHKD03
148	Weight and Measurement Act : Weight and Measurement Act	JHKD03
89	ADMINISTRATIVE SUITE : ADMINISTRATIVE SUITE	JHKD04
138	APP. OF RES. SUBJUDICE U/S 10 : APP. OF RES. SUBJUDICE U/S 10	JHKD04
23	ARBITRATION CASE : ARBITRATION CASE	JHKD04
47	ARBITRATION R.D. : ARBITRATION R.D.	JHKD04
169	Civil Execution Case : civil execution case	JHKD04
170	Civil Misc. Case : civil misc. case	JHKD04
48	CONTEMPT PROCEEDINGS : CONTEMPT PROCEEDINGS	JHKD04
98	CROSS SUIT : CROSS SUIT	JHKD04
100	DECLARATIVCE SUIT : DECLARATIVCE SUIT	JHKD04
90	DISSOLUTION OF PATNERSHIP : DISSOLUTION OF PATNERSHIP	JHKD04
8	ELECT. PETN : ELECT. PETN	JHKD04
204	Eviction Suit : EVICTION SUIT	JHKD04
104	EVICTION SUIT(U/S BBC ACT/ U/S 111 TP ACT) : EVICTION SUIT(U/S BBC ACT/ U/S	JHKD04
165	C1 : C1 cases	JHGM03
112	EXECUITION CASES : EXECUITION CASES	JHKD04
33	FINAL DECREE : FINAL DECREE	JHKD04
135	INJUNCTION O-39 , R1, 2 : INJUNCTION O-39 , R1, 2	JHKD04
35	INSOLVENCY : INSOLVENCY	JHKD04
97	INTERPLEADER SUIT : INTERPLEADER SUIT	JHKD04
109	LAND ACQUISTAION CASES : LAND ACQUISTAION CASES	JHKD04
5	LAND REF. : LAND REF.	JHKD04
146	L.A.ref : L.A.Ref	JHKD04
110	LUNACY/ ADOPTION/ INSOLVANCY : LUNACY/ ADOPTION/ INSOLVANCY	JHKD04
105	MISC CASES O-9 : MISC CASES  O-9  R-4	JHKD04
208	Misc Civil Application : Misc Civil Application	JHKD04
103	MONEY SUIT : MONEY SUIT	JHKD04
143	Original Suit : Title Suit	JHKD04
17	REG. CIVIL SUIT : REG. CIVIL SUIT	JHKD04
28	REGULAR PETITION : REGULAR PETITION	JHKD04
120	RESTORATION OF SUITS 0-9 R-4 , 0-9 , R -9 : RESTORATION OF SUITS 0-9 R-4 ,	JHKD04
209	REVOCATION CASE : REVOCATION CASE	JHKD04
121	SETTING  ASIDE EXPARTE ORDER 0-9 - R -13 : SETTING  ASIDE EXPARTE ORDER 0	JHKD04
19	SPL.CIV. SUIT : SPL.CIV. SUIT	JHKD04
29	SPL. DARKHAST : SPL. DARKHAST	JHKD04
94	SUIT BNY PERSON OF UNSOIUND MIND : SUIT BNY PERSON OF UNSOIUND MI	JHKD04
92	SUIT BY  CORPORATION/FIRM/TRUSTEE/INDEGENT PERSON : SUIT BY  CORPORATION/FIRM/TRUS	JHKD04
93	SUIT BY MINOR : SUIT BY MINOR	JHKD04
91	SUITE BY GOVT OR PUBLIC  OFFICERS IN OFFICIAL CAPA : SUITE BY GOVT OR PUBLIC  OFFIC	JHKD04
101	SUIT FOR PERPERTUAL INJECTION : SUIT FOR PERPERTUAL INJECTION	JHKD04
99	SUIT FOR SPECIAL PERFORMANCE OF CONTRACT : SUIT FOR SPECIAL PERFORMANCE O	JHKD04
96	SUIT OF PUBLIC NAUSIANCE : SUIT OF PUBLIC NAUSIANCE	JHKD04
22	SUMMARY CASE : SUMMARY CASE	JHKD04
95	SUMMARY SUIT : SUMMARY SUIT	JHKD04
154	Title Mortgage Suit : Title Mortgage Suit	JHKD04
174	Title (Partion) Suit : Title (partion) Suit	JHKD04
206	Title Suit : Title Partion Suit	JHKD04
89	ADMINISTRATIVE SUITE : ADMINISTRATIVE SUITE	JHKD02
138	APP. OF RES. SUBJUDICE U/S 10 : APP. OF RES. SUBJUDICE U/S 10	JHKD02
23	ARBITRATION CASE : ARBITRATION CASE	JHKD02
47	ARBITRATION R.D. : ARBITRATION R.D.	JHKD02
169	Civil Ex Case : Civil Execution Case	JHKD02
170	Civil Misc  Case : Civil Misc Case	JHKD02
48	CONTEMPT PROCEEDINGS : CONTEMPT PROCEEDINGS	JHKD02
98	CROSS/COUNTER SUIT : CROSS/COUNTER SUIT	JHKD02
100	DECLARATIVCE SUIT : DECLARATIVCE SUIT	JHKD02
90	DISSOLUTION OF PATNERSHIP : DISSOLUTION OF PATNERSHIP	JHKD02
8	ELECT. PETN : ELECT. PETN	JHKD02
204	Eviction Suit : EVICTION SUIT	JHKD02
104	EVICTION SUIT US BBC ACT US 111 TP ACT : EVICTION SUIT US BBC ACT US	JHKD02
112	EXECUTION CASES : EXECUTION CASES	JHKD02
135	INJUNCTION O-39 , R1, 2 : INJUNCTION O-39 , R1, 2	JHKD02
35	INSOLVENCY : INSOLVENCY	JHKD02
97	INTERPLEADER SUIT : INTERPLEADER SUIT	JHKD02
208	Land Acq Exe Case : Land Acquisition Execution	JHKD02
209	Land Acq. Misc. Case : Land Acquisition Misc.	JHKD02
109	LAND ACQUISITION CASES : LAND ACQUISITION CASES	JHKD02
146	Land Acqusition Case : Land Acqusition Case	JHKD02
5	LAND REF. : LAND REF.	JHKD02
110	LUNACY/ ADOPTION/ INSOLVANCY : LUNACY/ ADOPTION/ INSOLVANCY	JHKD02
105	MISC CASES : MISC CASES	JHKD02
210	Misc. Civil Application : Misc. Civil Application	JHKD02
158	Miscellaneous : Miscellaneous	JHKD02
183	Misc(non-judl.)cases : Misc(Non-Judicial)Cases	JHKD02
103	MONEY SUIT : MONEY SUIT	JHKD02
42	Original Suit : Original Suit	JHKD02
102	PARTION (Title) SUIT : PARTION TITLE SUIT	JHKD02
45	PAUPER APPLICATION : PAUPER APPLICATION	JHKD02
17	REG. CIVIL SUIT : REG. CIVIL SUIT	JHKD02
120	RESTORATION OF SUITS : RESTORATION OF SUITS	JHKD02
212	REVOCATION CASE : REVOCATION CASE	JHKD02
121	SETTING  ASIDE EXPARTE ORDER 0-9 - R -13 : SETTING  ASIDE EXPARTE ORDER 0	JHKD02
19	SPL.CIV SUIT : SPL.CIV. SUIT	JHKD02
94	SUIT BNY PERSON OF UNSOIUND MIND : SUIT BNY PERSON OF UNSOIUND MI	JHKD02
92	SUIT BY  CORPORATION/FIRM/TRUSTEE/INDEGENT PERSON : SUIT BY  CORPORATION/FIRM/TRUS	JHKD02
93	SUIT BY MINOR : SUIT BY MINOR	JHKD02
91	SUITE BY GOVT OR PUBLIC  OFFICERS IN OFFICIAL CAPA : SUITE BY GOVT OR PUBLIC  OFFIC	JHKD02
101	SUIT FOR PERPERTUAL INJECTION : SUIT FOR PERPERTUAL INJECTION	JHKD02
99	SUIT FOR SPECIAL PERFORMANCE OF CONTRACT : SUIT FOR SPECIAL PERFORMANCE O	JHKD02
96	SUIT OF PUBLIC NAUSIANCE : SUIT OF PUBLIC NAUSIANCE	JHKD02
95	SUMMARY SUIT : SUMMARY SUIT	JHKD02
175	Title arbit Suit : Title Arbitration Suit	JHKD02
154	Title Mortgage Suit : Title Mortgage Suit	JHKD02
174	Title Partion suit : Title partion Suit	JHKD02
143	Title Suit : Title Suit Partition	JHKD02
152	Anticipatory Bail : Anticipatory Bail Petition	JHKD01
165	Bail Petition : BAIL PETITION	JHKD01
1	CIVIL APPEAL : TITLE APPEAL	JHKD01
2	Civil Misc Appeal : Civil Misc Appeal	JHKD01
215	Commercial Appeal : Commercial Appeal	JHKD01
214	Commercial Suit : Commercial Suit	JHKD01
211	Complaint case- (Drug and Cosmetic) : Drug and Cosmetic	JHKD01
153	Complaint Case(SC/ST) : Complaint Case(SC/ST)	JHKD01
8	Complaint(POCSO) : Complaint(POCSO)	JHKD01
173	C.P : Complaint Case	JHKD01
193	Cr. Appeal (Special Children Court) : Cr. Appeal(Special Children Court)	JHKD01
12	Criminal Appeal : CRI. APPEAL	JHKD01
217	Criminal Misc. Case : Criminal Misc. Case	JHKD01
13	Criminal Revision : CRI. REVISION	JHKD01
112	EXECUITION CASES : EXECUITION CASES	JHKD01
33	FINAL DECREE : FINAL DECREE	JHKD01
164	G.R. : General Register No.	JHKD01
213	GR Case (Electricity) : Electricity Case	JHKD01
161	GUARDIANSHIP Case : GUARDIANSHIP	JHKD01
35	INSOLVENCY Case : INSOLVENCY Case	JHKD01
109	LAND ACQUISTAION CASES : LAND ACQUISTAION CASES	JHKD01
5	Land Reference : LAND REF.	JHKD01
146	L.A.Ref : L.A.Ref	JHKD01
195	Letter Of Admn. : Letter Of Administration	JHKD01
105	MISC CASES  O-9 , : MISC CASES O-9 , R-4  b) O-	JHKD01
210	Misc Civil Application : Misc Civil Application	JHKD01
209	Misc Cri. Application : Misc Cri. Application	JHKD01
157	Miscellaneous Petition : Criminal(Misc.)CASE	JHKD01
106	Mot Acci Claim Cases : CLAIM CASES	JHKD01
15	NDPS. S. CASE : NDPS. S. CASE	JHKD01
198	Non F I R : Non F I R	JHKD01
143	Original Suit : Title Suit	JHKD01
108	PROBATE CASE : PROBATE CASE	JHKD01
170	Revocation Case : Misc. cases	JHKD01
206	SC/ ST CASES : SC/ ST CASES	JHKD01
216	SESSIONS TRIAL(RAILWAY-GRP) : SESSIONS TRIAL(RAILWAY-GRP)	JHKD01
10	SESSION TRIAL : SESSION TRIAL	JHKD01
212	Special Children Case : Special Children Case	JHKD01
207	Special POCSO Case : Prevt. of Child Sexual Offence	JHKD01
111	SUCESSION CERTIFICATE CASES : SUCESSION CERTIFICATE CASES	JHKD01
81	SUM. CIVIL SUIT : SUM. CIVIL SUIT	JHKD01
175	Title (Arbitration)suit : Title Arbitration Suit	JHKD01
155	Title Eviction Suit : Title Eviction Suit	JHKD01
154	Title Mortgage Suit : Title Mortgage Suit	JHKD01
174	Title(partion)Suit : Title(partion)Suit	JHKD01
171	Transfer Petition Cr. : Misc. Transfer petition	JHKD01
218	Transfer Petiton Civil : Misc. Transfer Petiton	JHKD01
43	TRUST APPEAL : TRUST APPEAL	JHKD01
44	TRUST SUIT : TRUST SUIT	JHKD01
148	Weight and Measurement Act : Weight and Measurement Act	JHKD01
170	Civil Misc. Case : civil misc. case	JHKD05
209	Cr. Misc. Application : Cr. Misc. Application	JHKD05
112	EXECUITION CASES : EXECUITION CASES	JHKD05
161	GUARDIANSHIP : GUARDIANSHIP	JHKD05
208	Maintenance (Alteration) Case : Maintenance (Alteration) Case	JHKD05
206	Maintenance Alteration Case : Maintenance Alteration Case	JHKD05
156	Original Maintenance : Maintenance	JHKD05
207	Original (Maintenence) : Original (Maintenace)	JHKD05
107	Original Suit : MATRIMONIAL CASE	JHKD05
175	C1 : C1	JHCB03
196	C1.s : C1.s	JHCB03
199	C1.s1 : C1.s1	JHCB03
176	C2 : C2	JHCB03
177	C3 : C3	JHCB03
197	C3.s : C3.s	JHCB03
200	C3.s1 : C3.s1	JHCB03
178	C7 : C7	JHCB03
173	Civil Misc.case : Civil MISC.CASE	JHCB03
195	Complaint Case : COMPLAINT CASE	JHCB03
157	Criminal Mislaneous : Criminal Mislaneous	JHCB03
105	Cri. Misc.case : Cri. MISC.CASE	JHCB03
164	cr. Misc. P. Case : Criminal Misc. P. Case	JHCB03
205	E.C. Cases : E.C. Cases	JHCB03
184	Election Petition : ELECTION PETITION	JHCB03
209	Enquiry Case (Juvenile) : Enquiry Case (Juvenile)	JHCB03
104	Eviction Suit : EVICTION SUIT	JHCB03
112	Ex. Cases : EXECUTION CASES	JHCB03
33	FINAL DECREE : FINAL DECREE	JHCB03
162	G.R.case : G.R.case	JHCB03
189	G.R. Pocso : G.R. Pocso	JHCB03
204	GR.POCSO.s : GR.POCSO.s	JHCB03
190	G.R.s : G.R. suplimentary	JHCB03
193	G.R.s1 : G.R.s1	JHCB03
194	G.R.s2 : G.R.s2	JHCB03
109	LAND ACQUISITION CASE : LAND ACQUISITION CASE	JHCB03
41	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHCB03
40	MISC Cri. APPLICATION : MISC Cri. APPLICATION	JHCB03
185	Misc. P. Case : Misc. P. Case	JHCB03
103	MONEY SUIT : MONEY SUIT	JHCB03
143	Original Suit : ORIGINAL SUIT	JHCB03
201	Revocation Case : Revocation Case	JHCB03
10	S T : SESSIONS TRIAL	JHCB03
172	Title Partition Suit : TITLE PARTITION SUIT	JHCB03
23	ARBITRATION CASE : ARBITRATION CASE	JHCB02
173	Civil Misc.case : Civil MISC.CASE	JHCB02
196	Commercial Arbitration : Commercial Arbitration	JHCB02
195	Commercial Execution : Comm. Exec	JHCB02
197	Commercial Revocation : Commercial Revocation	JHCB02
194	Commercial Suit : Commercial Suit	JHCB02
184	Election Petition : ELECTION PETITION	JHCB02
104	Eviction Suit : EVICTION SUIT	JHCB02
112	Ex. Cases : EXECUTION CASES	JHCB02
33	FINAL DECREE : FINAL DECREE	JHCB02
109	LAND ACQUISITION CASE : LAND ACQUISITION CASE	JHCB02
41	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHCB02
103	MONEY SUIT : MONEY SUIT	JHCB02
143	Original Suit : Original SUIT	JHCB02
172	Title Partition Suit : TITLE PARTITION SUIT	JHCB02
182	Title p. Suit For Final Decree : Title p. Suit For Final Decree	JHCB02
176	B f : Bihar Forest Act	JHDH03
193	T.P.S.s : Title P. Suit. S	JHCB02
152	Antic.Bail Petition : Anticipatory Bail Petition	JHCB01
153	Bail Petition : Bail Petition	JHCB01
175	C.1 : C.1	JHCB01
196	C/1.s : C/1.s	JHCB01
177	C.3 : C.3	JHCB01
197	C/3.s : C/3.s	JHCB01
178	C.7 : C.7	JHCB01
210	Children Case : Children Case	JHCB01
1	CIVIL APPEAL : CIVIL APPEAL	JHCB01
166	Civil Misc.Appeal : CIVIL MISC.APPEAL	JHCB01
213	Commercial Appeal : Commercial Appeal	JHCB01
217	Commercial Arbitration : Commercial Arbitration	JHCB01
216	Commercial Execution : Commercial Execution	JHCB01
218	Commercial Revocation : Commercial Revocation	JHCB01
212	Commercial Suit : Commercial Suit	JHCB01
195	Complaint Case : Complaint Case	JHCB01
163	Confiscation Appeal : Confiscation Appeal	JHCB01
12	Criminal Appeal : CRIMINAL APPEAL	JHCB01
164	Criminal Misc. : Criminal Misc.	JHCB01
13	Criminal Revision : Criminal Revision	JHCB01
214	Drugs And Cosmetic Cases : Drugs and Cosmetic Case	JHCB01
205	E.C. Cases : E.C. Cases	JHCB01
184	Election Petition : ELECTION PETITION	JHCB01
211	Electricity Act Cases : Electricity Act Cases	JHCB01
215	Enquiry Case (Juvenile) : Enquiry Case (Juvenile)	JHCB01
168	Eviction Appeal : EVICTION APPEAL	JHCB01
104	Eviction Suit : EVICTION SUIT	JHCB01
112	Execution Case : EXECUTION CASE	JHCB01
162	G.R.case : G.R.case	JHCB01
190	G.R.s : G.R. suplimentary	JHCB01
193	G.R.s1 : G.R.s1	JHCB01
194	G.R.s2 : G.R.s2	JHCB01
161	GUARDIANSHIP CASE : GUARDIANSHIP CASE	JHCB01
109	LAND ACQUI. CASE : LAND ACQUI. CASE	JHCB01
146	Letter of Admin.Case : Letter of  Administration Case	JHCB01
173	Misc.Case : Misc.Case	JHCB01
41	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHCB01
40	MISC Cri. APPLICATION : MISC Cri. APPLICATION	JHCB01
187	Misc (p). Case : Misc.Petition Case	JHCB01
169	Mot.Acci.Claims Case : Motor Accident Claims Case	JHCB01
188	N.D.P.S. Case : N.D.P.S. Case	JHCB01
203	NDPS.s : NDPS Suppl	JHCB01
207	NDPSs1 : NDPSs1	JHCB01
143	Original Suit : ORIGINAL SUIT	JHCB01
108	PROBATE CASE : PROBATE CASE	JHCB01
179	R.A.case : R.A.case	JHCB01
201	Revocation Case : Revocation Case	JHCB01
192	SC-ST Case : SC-ST Case	JHCB01
202	SC-ST.s : SC-ST.s case	JHCB01
10	Sessions Trial : SESSIONS TRIAL	JHCB01
189	Spl POCSO Case : Spl POCSO Case	JHCB01
204	Spl POCSOs : Spl POCSOs	JHCB01
191	S.T.s : S.T. supplimentary	JHCB01
198	S.T.s1 : S.T.s1	JHCB01
209	S.T.s2 : S.T.s2	JHCB01
111	Succession Cert.Case : SUCCESSION CERTIFICATE CASES	JHCB01
167	Title. P.Appeal : Title. P. Appeal	JHCB01
172	Title. P. Suit : TITLE P. SUIT	JHCB01
208	Transfer Petition : Transfer Petition	JHCB01
183	Vigilance Case : Vigilance Case	JHCB01
206	Vigilance Case(S) : Vigilance Case(S)	JHCB01
175	C1 : C1	JHCB06
196	C1.s : C1.s	JHCB06
199	C1.s1 : C1.s1	JHCB06
176	C2 : C2	JHCB06
177	C3 : C3	JHCB06
197	C3.s : C3.s	JHCB06
200	C3.s1 : C3.s1	JHCB06
178	C7 : C7	JHCB06
173	Civil Misc.case : Civil Misc.case	JHCB06
195	Complaint Case : COMPLAINT CASE	JHCB06
105	Cri. Misc.case : Cri. Misc.case	JHCB06
205	E.C. Cases : E.C. Cases	JHCB06
104	Eviction Suit : EVICTION SUIT	JHCB06
112	Ex. Cases : EXECUTION CASES	JHCB06
162	G R case : G R case	JHCB06
190	G.R.s : G.R.s	JHCB06
193	G.R. s1 : G.R. s1	JHCB06
194	G.R. s2 : G.R. s2	JHCB06
41	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHCB06
40	MISC Cri. APPLICATION : MISC Cri. APPLICATION	JHCB06
185	Misc. Petition Case : Misc. Petition Case	JHCB06
143	Original Suit : Original SUIT	JHCB06
192	SC-ST case : SC-ST case	JHCB06
10	S T : SESSIONS TRIAL	JHCB06
172	Title P. Suit : Title P. Suit	JHCB06
173	Civil Misc.case : Civil MISC.CASE	JHCB05
112	Ex. Cases : EXECUTION CASES	JHCB05
161	GUARDIANSHIP : GUARDIANSHIP	JHCB05
185	Maint. Alt. Case : Maint. Alt. Case	JHCB05
174	Original Suit : Original Suit	JHCB05
105	Orig. Maintenc. Case : Original Maintenance Case	JHCB05
89	ADMINISTRATIVE SUITE : ADMINISTRATIVE SUITE	JHGM04
138	APP. OF RES. SUBJUDICE U/S 10 : APP. OF RES. SUBJUDICE U/S 10	JHGM04
23	ARBITRATION CASE : ARBITRATION CASE	JHGM04
47	ARBITRATION R.D. : ARBITRATION R.D.	JHGM04
174	CAVEAT : CAVEAT	JHGM04
163	CIVIL MISC CASE : CIVIL MISCELLANEOUS CASE	JHGM04
48	CONTEMPT PROCEEDINGS : CONTEMPT PROCEEDINGS	JHGM04
98	CROSS SUIT : CROSS SUIT	JHGM04
100	DECLARATIVCE SUIT : DECLARATIVCE SUIT	JHGM04
90	DISSOLUTION OF PATNERSHIP : DISSOLUTION OF PATNERSHIP	JHGM04
8	ELECT. PETN : ELECT. PETN	JHGM04
171	E.T.S : Eviction Title Suit	JHGM04
185	B o c Act : B O C ACT	JHDH03
104	EVICTION SUIT : EVICTION SUIT(U/S BBC ACT/ U/S	JHGM04
112	EXECUTION CASES : EXECUTION CASES	JHGM04
33	FINAL DECREE : FINAL DECREE	JHGM04
37	GUARDIAN amp WARDS CASE : GUARDIAN amp WARDS CASE	JHGM04
135	INJUNCTION O-39 , R1, 2 : INJUNCTION O-39 , R1, 2	JHGM04
35	INSOLVENCY : INSOLVENCY	JHGM04
97	INTERPLEADER SUIT : INTERPLEADER SUIT	JHGM04
146	L.A : L.A	JHGM04
109	LAND ACQUISTAION CASES : LAND ACQUISTAION CASES	JHGM04
5	LAND REF. : LAND REF.	JHGM04
110	LUNACY/ ADOPTION/ INSOLVANCY : LUNACY/ ADOPTION/ INSOLVANCY	JHGM04
38	MESNE PROFIT : MESNE PROFIT	JHGM04
105	MISC CASES a) O-9 , R-4  b) O-9, R-9 c) U/S 47CPC : MISC CASES a) O-9 , R-4  b) O-	JHGM04
172	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHGM04
176	Misc Petition : Misc Petition	JHGM04
158	Mislaneous : Mislaneous	JHGM04
173	ORIGINAL SUIT : ORIGINAL SUIT	JHGM04
102	Partition Suit : PARTITION SUIT	JHGM04
45	PAUPER APPLICATION : PAUPER APPLICATION	JHGM04
170	P.Pro : PARTITION PROCEEDING	JHGM04
17	REG. CIVIL SUIT : REG. CIVIL SUIT	JHGM04
28	REGULAR PETITION : REGULAR PETITION	JHGM04
42	RENT SUIT : RENT SUIT	JHGM04
120	RESTORATION OF SUITS 0-9 R-4 , 0-9 , R -9 : RESTORATION OF SUITS 0-9 R-4 ,	JHGM04
121	SETTING  ASIDE EXPARTE ORDER 0-9 - R -13 : SETTING  ASIDE EXPARTE ORDER 0	JHGM04
41	SMALL CAUSE SUIT : SMALL CAUSE SUIT	JHGM04
19	SPL.CIV. SUIT : SPL.CIV. SUIT	JHGM04
29	SPL. DARKHAST : SPL. DARKHAST	JHGM04
94	SUIT BNY PERSON OF UNSOIUND MIND : SUIT BNY PERSON OF UNSOIUND MI	JHGM04
92	SUIT BY  CORPORATION/FIRM/TRUSTEE/INDEGENT PERSON : SUIT BY  CORPORATION/FIRM/TRUS	JHGM04
93	SUIT BY MINOR : SUIT BY MINOR	JHGM04
91	SUITE BY GOVT OR PUBLIC  OFFICERS IN OFFICIAL CAPA : SUITE BY GOVT OR PUBLIC  OFFIC	JHGM04
101	SUIT FOR PERPERTUAL INJECTION : SUIT FOR PERPERTUAL INJECTION	JHGM04
99	SUIT FOR SPECIAL PERFORMANCE OF CONTRACT : SUIT FOR SPECIAL PERFORMANCE O	JHGM04
96	SUIT OF PUBLIC NAUSIANCE : SUIT OF PUBLIC NAUSIANCE	JHGM04
22	SUMMARY CASE : SUMMARY CASE	JHGM04
95	SUMMARY SUIT : SUMMARY SUIT	JHGM04
154	Title Mortgage Suit : Title Mortgage Suit	JHGM04
143	Title Suit : Title Suit	JHGM04
152	A.B.P : Anticipatory Bail PETITION	JHGM01
23	ARBITRATION CASE : ARBITRATION CASE	JHGM01
153	B.P : BAIL PETITION	JHGM01
202	Children Case : Children Case	JHGM01
173	CIVIL APPEAL : CIVIL APPEAL	JHGM01
2	CIVIL MISC. APPEAL : CIVIL MISC. APPEAL	JHGM01
218	Coal Bearing Cases : Coal Bearing Cases	JHGM01
203	Comm. Case : Comm. Case	JHGM01
235	Commercial Appeals : Commercial Appeals	JHGM01
220	Commercial Revocation : Commercial Revocation	JHGM01
236	Commercial Suits : Commercial Suits	JHGM01
219	Comm. Exec : Comm. Exec	JHGM01
186	COMPLAINT CASE : COMPLAINT	JHGM01
222	COMPLAINT CASE SPT-I : COMPLAINT CASE SPT-I	JHGM01
12	CRIMINAL APPEAL : CRIMINAL APPEAL	JHGM01
30	CRIMINAL MISC. : OTHER MISC. CRI. APPLN.	JHGM01
13	CRIMINAL REVISION : CRIMINAL REVISION	JHGM01
201	CYBER CASE : CYBER CASE	JHGM01
216	Drug Cosmetic : Drug Cosmetic	JHGM01
16	E.A.C : ELECTRICITY ACT CASES	JHGM01
229	E.A.C SPT. : ELECTRICITY ACT CASE	JHGM01
112	EXECUTION CASES : EXECUTION CASES	JHGM01
33	FINAL DECREE : FINAL DECREE	JHGM01
194	G.R : General Register	JHGM01
223	G.R SPT-I : GENERAL REGISTER SPLIT CASE	JHGM01
225	G.R SPT-II : G.R SPT-II	JHGM01
161	GUARDIANSHIP CASES : GUARDIANSHIP CASES	JHGM01
35	INSOLVENCY CASE : INSOLVENCY CASE	JHGM01
167	L.A.A : LAND ACQUISTAION APPEAL	JHGM01
109	LAND ACQUISTAION CASES : LAND ACQUISTAION CASES	JHGM01
206	L.A.Ref. : L.A.Ref.	JHGM01
146	Letter of Administration Case : LETTER OF ADMINISTRATION CASE	JHGM01
106	M.A.C.C : MOTOR ACCIDENT CLAIMS CASES	JHGM01
198	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHGM01
199	MISC. CRIMINAL APPLICATION : MISC. CRIMINAL APPLICATION	JHGM01
221	MISCELLANEOUS CASES MACT : MISCELLANEOUS CASES MACT	JHGM01
204	MiscRC : MiscRC	JHGM01
15	N.D.P.S : N.D.P.S CASE	JHGM01
195	N.D.P.S SPT I : N.D.P.S SPT I	JHGM01
214	Non F I R : Non F I R	JHGM01
143	ORIGINAL SUIT : ORIGINAL SUIT	JHGM01
208	POTA Case : POTA Case	JHGM01
108	PROBATE CASE : PROBATE CASE	JHGM01
163	REVOCATION CASE : REVOCATION CASE	JHGM01
192	SC/ST Case : SC/ST Cases	JHGM01
228	SC/ST Case SPT-I : SPLIT UP SC/ST CASE	JHGM01
234	SC/ST Case SPT-II : SC/ST Case SPLIT CASE-I	JHGM01
10	SESSION TRIAL : SESSION TRIAL	JHGM01
179	SPECIAL POCSO CASE : SPECIAL CASES POCSO ACT	JHGM01
224	SPECIAL POCSO CASE SPT : POCSO SPLIT UP CASE I	JHGM01
227	SPECIAL POCSO CASE SPT II : POCSO SPLIT UP CASE II	JHGM01
230	SPECIAL POCSO CASE SPT III : SPECIAL POCSO CASE SPLIT UP III	JHGM01
232	SPECIAL POCSO CASE SPT IV : SPECIAL POCSO CASE SPLIT UP IV	JHGM01
209	SplNIA : SPlNIA	JHGM01
170	S.T. Spt : SESSION TRIAL SPT I	JHGM01
172	S.T. spt II : SESSION TRIAL SPT II	JHGM01
196	S.T. spt III : SESSION TRIAL SPT III	JHGM01
89	ADMINISTRATIVE SUITE : ADMINISTRATIVE SUITE	JHGM02
138	APP. OF RES. SUBJUDICE U/S 10 : APP. OF RES. SUBJUDICE U/S 10	JHGM02
23	ARBITRATION CASE : ARBITRATION CASE	JHGM02
47	ARBITRATION R.D. : ARBITRATION R.D.	JHGM02
178	Civil Execution Case : Civil Execution Case	JHGM02
173	CIVIL MISC. CASE : CIVIL MISCLENEOUS CASE	JHGM02
185	Commercial Appeals : Commercial Appeals	JHGM02
184	Commercial Suits : Commercial Suits	JHGM02
48	CONTEMPT PROCEEDINGS : CONTEMPT PROCEEDINGS	JHGM02
98	CROSS SUIT : CROSS SUIT	JHGM02
100	DECLARATIVCE SUIT : DECLARATIVCE SUIT	JHGM02
90	DISSOLUTION OF PATNERSHIP : DISSOLUTION OF PATNERSHIP	JHGM02
183	ECO. Off. : ECO. Off.	JHGM02
8	ELECT. PETN : ELECT. PETN	JHGM02
104	EVICTION SUIT(U/S BBC ACT/ U/S 111 TP ACT) : EVICTION SUIT(U/S BBC ACT/ U/S	JHGM02
112	EXECUTION CASES : EXECUTION CASES	JHGM02
135	INJUNCTION O-39 , R1, 2 : INJUNCTION O-39 , R1, 2	JHGM02
35	INSOLVENCY : INSOLVENCY	JHGM02
97	INTERPLEADER SUIT : INTERPLEADER SUIT	JHGM02
109	LAND ACQUISITION CASES : LAND ACQUISITION CASES	JHGM02
176	Land Acquisition Execution Case : Land Acquisition Execution Cas	JHGM02
177	Land Acqusition Misc Case : Land Acqusition Misc Case	JHGM02
110	LUNACY/ ADOPTION/ INSOLVANCY : LUNACY/ ADOPTION/ INSOLVANCY	JHGM02
38	MESNE PROFIT : MESNE PROFIT	JHGM02
105	MISC CASES : MISC CASES	JHGM02
163	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHGM02
181	Misc(Non-Judicial) Cases : Misc(Non-Judicial) Cases	JHGM02
179	Misc Petition : Misc Petition	JHGM02
158	Mislaneous : Mislaneous	JHGM02
103	MONEY SUIT : MONEY SUIT	JHGM02
172	ORIGINAL SUIT : ORIGINAL SUIT	JHGM02
102	Partition Suit : PARTITION SUIT	JHGM02
45	PAUPER APPLICATION : PAUPER APPLICATION	JHGM02
17	REG. CIVIL SUIT : REG. CIVIL SUIT	JHGM02
42	RENT SUIT : RENT SUIT	JHGM02
120	RESTORATION OF SUITS 0-9 R-4 , 0-9 , R -9 : RESTORATION OF SUITS 0-9 R-4 ,	JHGM02
121	SETTING  ASIDE EXPARTE ORDER 0-9 - R -13 : SETTING  ASIDE EXPARTE ORDER 0	JHGM02
41	SMALL CAUSE SUIT : SMALL CAUSE SUIT	JHGM02
19	SPL.CIV. SUIT : SPL.CIV. SUIT	JHGM02
94	SUIT BNY PERSON OF UNSOIUND MIND : SUIT BNY PERSON OF UNSOIUND MI	JHGM02
92	SUIT BY  CORPORATION/FIRM/TRUSTEE/INDEGENT PERSON : SUIT BY  CORPORATION/FIRM/TRUS	JHGM02
93	SUIT BY MINOR : SUIT BY MINOR	JHGM02
91	SUITE BY GOVT OR PUBLIC  OFFICERS IN OFFICIAL CAPA : SUITE BY GOVT OR PUBLIC  OFFIC	JHGM02
101	SUIT FOR PERPERTUAL INJECTION : SUIT FOR PERPERTUAL INJECTION	JHGM02
99	SUIT FOR SPECIAL PERFORMANCE OF CONTRACT : SUIT FOR SPECIAL PERFORMANCE O	JHGM02
96	SUIT OF PUBLIC NAUSIANCE : SUIT OF PUBLIC NAUSIANCE	JHGM02
95	SUMMARY SUIT : SUMMARY SUIT	JHGM02
180	Title Arbitration Suit : Title Arbitration Suit	JHGM02
154	Title Mortgage Suit : Title Mortgage Suit	JHGM02
143	Title (Partion) Suit : Title (Partion) Suit	JHGM02
182	Title (Trade Mark) Suit : Title (Trade Mark) Suit	JHGM02
226	S.T. SPT IV : SESSION TRIAL SPT IV	JHGM01
231	S.T. SPT V : SESSION TRIAL SPT V	JHGM01
233	S.T. SPT VI : SESSION TRIAL SPT VI	JHGM01
111	SUCCESSION CERTIFICATE CASES : SUCCESSION CERTIFICATE CASES	JHGM01
81	SUM. CIVIL SUIT : SUM. CIVIL SUIT	JHGM01
1	TITLE APPEAL : TITLE APPEAL	JHGM01
212	Title Arbitration Suit : Title Arbitration Suit	JHGM01
155	Title Eviction Suit : Title Eviction Suit	JHGM01
154	Title Mortgage Suit : Title Mortgage Suit	JHGM01
211	Title(Partion) Suit : Title(Partion) Suit	JHGM01
215	Title (Trade Mark) Suit : Title (Trade Mark) Suit	JHGM01
175	TRANSFER PETITION : TRANSFER PETITION	JHGM01
43	TRUST APPEAL : TRUST APPEAL	JHGM01
44	TRUST SUIT : TRUST SUIT	JHGM01
205	TS-PS/MOR/DEC/ARB : TS-PS/MOR/DEC/ARB	JHGM01
217	VIG. COMPLAINT : VIG. COMPLAINT	JHGM01
213	Vigilance Case : Vigilance Case	JHGM01
148	Weight and Measurement Act : Weight and Measurement Act	JHGM01
163	CIVIL MISC. CASE : CIVIL MISC. CASE	JHGM05
112	EXECUTION CASES : EXECUTION CASES	JHGM05
161	GUARDIANSHIP : GUARDIANSHIP	JHGM05
171	MAINT. ALTERATION : MAINTENANCE ALTERATION CASE	JHGM05
173	Misc F : Misc F	JHGM05
174	Misc M : Misc M	JHGM05
156	ORG. MAINTENANCE CASE : ORG. MAINTENANCE	JHGM05
23	Arbitration Case : Arbitration Case	JHCH02
176	Commercial Suit : Commercial Suit	JHCH02
112	Execution Case : EXECUTION CASE	JHCH02
168	Land Acq. Misc. Case : Land Acq. Misc. Case	JHCH02
109	Land Acquisition Case : Land Acquistaion Case	JHCH02
105	Misc. Case : Misc. Case	JHCH02
166	Misc. Civil Application : Misc. Civil Application	JHCH02
172	ORIGINAL SUIT : ORIGINAL SUIT M.T.S.	JHGM05
188	C1 Spt : Split up C1 Cases	JHGM03
166	C2 : C2 cases	JHGM03
177	COMPLAINT CASE : COMPLAINT CASE	JHGM03
185	COMPLAINT CASE SPT-I : COMPLAINT CASE SPT-I	JHGM03
194	COMPLAINT CASE SPT-II : COMPLAINT CASE SPT-II	JHGM03
173	COMPL CASE spt1 : CRIMINAL COMPLAINT CASE SPT-1	JHGM03
159	Cr. Case Complaint (O) : Cr. Case Complaint (O)	JHGM03
142	Cr. Case Complaint (P) : Cr. Case Complaint (P)	JHGM03
24	CRI. BAIL APPLN. : CRI. BAIL APPLN.	JHGM03
157	Cri Misc Case : Criminal Misclaneous	JHGM03
147	Domestic Violence Act 2005 : Domestic Violence Act 2005	JHGM03
192	EC ACT CASES : The Essential Commodities Act Cases	JHGM03
193	EC ACT CASES SPT : The Essential Commodities Act Cases Split Up	JHGM03
149	Excise Act : Excise Act	JHGM03
21	G.R : GENERAL REGISTER	JHGM03
170	G.R Spt : GENERAL REGISTER spt-I	JHGM03
171	G.R spt II : GENERAL REGISTER spt-II	JHGM03
172	G.R spt III : GENERAL REGISTER spt-III	JHGM03
175	G.R spt IV : GENERAL REGISTER spt-IV	JHGM03
176	G.R spt V : GENERAL REGISTER spt-V	JHGM03
190	G.R spt VI : G.R spt VI	JHGM03
133	MESNS PROFIT 0-34 , R-10A : MESNS PROFIT 0-34 , R-10A	JHGM03
184	MISC. CRIMINAL APPLICATION : MISC. CRIMINAL APPLICATION	JHGM03
186	Misc Petition : Misc Petition	JHGM03
189	Non F I R : Non F I R	JHGM03
178	POLICE ACT CASES : POLICE ACT, 34 POLICE ACT	JHGM03
153	Regular Bail : Regular Bail	JHGM03
162	Rev. Appl. : Revenue Appeal	JHGM03
180	U.R CASES : U.R. CASES	JHGM03
148	Weight and Measurement Act : Weight and Measurement Act	JHGM03
160	Weight and measurement Act 1985 : Weight, measurement Act 1985	JHGM03
142	Complaint Case : Complaint Case	JHGD03
173	Complaint Case 1 : Complaint Case 1	JHGD03
159	Cr. Case Complaint (O) : Cr. Case Complaint (O)	JHGD03
24	CRI. BAIL APPLN. : CRI. BAIL APPLN.	JHGD03
21	CRI. CASE : CRI. CASE	JHGD03
175	Criminal Misc Case : Criminal Misc Case	JHGD03
80	DISTRESS WARRENT : DISTRESS WARRENT	JHGD03
147	Domestic Violence Act 2005 : Domestic Violence Act 2005	JHGD03
32	E.S.I. ACT  CASE : E.S.I. ACT  CASE	JHGD03
149	Excise Act : Excise Act	JHGD03
165	Gocr : GOCR	JHGD03
162	G.R. Cases : G.R. Cases	JHGD03
168	GR S : GR S	JHGD03
171	Gr S 1 : Gr S 1	JHGD03
172	Gr S II : Gr S II	JHGD03
38	Gr S III : Gr. S III	JHGD03
174	Juvenile Case : Juvenile Case	JHGD03
170	Misc. Criminal Application : Misc. Criminal Application	JHGD03
30	OTHER MISC. CRI. APPLN. : OTHER MISC. CRI. APPLN.	JHGD03
20	REG. CRI. CASE : REG. CRI. CASE	JHGD03
153	Regular Bail : Regular Bail	JHGD03
31	REVIEW APPLICATION : REVIEW APPLICATION	JHGD03
25	SPL. CRI. MA : SPL. CRI. MA	JHGD03
148	Weight and Measurement Act : Weight and Measurement Act	JHGD03
23	ARBITRATION CASE : ARBITRATION CASE	JHGD02
47	ARBITRATION R.D. : ARBITRATION R.D.	JHGD02
105	CIVIL MISC. CASES : CIVIL MISC. CASES	JHGD02
172	Commercial Appeals : Commercial Appeals	JHGD02
171	Commercial Suits : Commercial Suits	JHGD02
48	CONTEMPT PROCEEDINGS : CONTEMPT PROCEEDINGS	JHGD02
100	DECLARATIVCE SUIT : DECLARATIVCE SUIT	JHGD02
90	DISSOLUTION OF PATNERSHIP : DISSOLUTION OF PATNERSHIP	JHGD02
8	ELECT. PETN : ELECT. PETN	JHGD02
112	EXECUTION CASES : TITLE EXECUTION CASES	JHGD02
33	FINAL DECREE : FINAL DECREE	JHGD02
135	INJUNCTION O-39 , R1, 2 : INJUNCTION O-39 , R1, 2	JHGD02
35	INSOLVENCY : INSOLVENCY	JHGD02
109	LAND ACQUISTAION CASES : LAND ACQUISTAION CASES	JHGD02
170	Misc . Civil Application : Misc . Civil Application	JHGD02
103	MONEY SUIT : MONEY SUIT	JHGD02
102	ORIGINAL  SUIT : ORIGINAL  SUIT	JHGD02
45	PAUPER APPLICATION : PAUPER APPLICATION	JHGD02
17	REG. CIVIL SUIT : REG. CIVIL SUIT	JHGD02
42	RENT SUIT : RENT SUIT	JHGD02
19	SPL.CIV. SUIT : SPL.CIV. SUIT	JHGD02
29	SPL. DARKHAST : SPL. DARKHAST	JHGD02
39	SUCCESSION Case : SUCCESSION Case	JHGD02
92	SUIT BY  CORPORATION/FIRM/TRUSTEE/INDEGENT PERSON : SUIT BY  CORPORATION/FIRM/TRUS	JHGD02
91	SUITE BY GOVT OR PUBLIC  OFFICERS IN OFFICIAL CAPA : SUITE BY GOVT OR PUBLIC  OFFIC	JHGD02
22	SUMMARY CASE : SUMMARY CASE	JHGD02
155	Title Eviction Suit : Title Eviction Suit	JHGD02
154	Title Mortgage Suit : Title Mortgage Suit	JHGD02
143	Title Suit : Title Suit	JHGD02
152	Anticipatory Bail Petition : Anticipatory Bail Petition	JHGD01
23	ARBITRATION CASE : ARBITRATION CASE	JHGD01
24	BAIL PETITION : BAIL PETITION	JHGD01
188	Children Case No. : Children Case No.	JHGD01
185	Children (POCSO) Case No. : Children (POCSO) Case No.	JHGD01
1	Civil Appeal : Civil Appeal	JHGD01
2	CIVIL MISC. APPEAL : CIVIL MISC. APPEAL	JHGD01
187	Commercial Appeals : Commercial Appeals	JHGD01
186	Commercial Suits : Commercial Suits	JHGD01
184	Complaint Case : Complaint Case	JHGD01
21	CRI. CASE : CRI. CASE	JHGD01
12	Criminal Appeal : Criminal Appeal	JHGD01
30	CRIMINAL.MISC : CRIMINAL.MISC	JHGD01
13	Criminal Revision : Criminal Revision	JHGD01
182	Electricity  Act Case : Electricity  Act Case	JHGD01
112	EXECUTION CASE : EXECUTION CASES	JHGD01
162	G.R. Case : GR	JHGD01
161	GUARDIANSHIP : GUARDIANSHIP	JHGD01
180	Human  Right Cases : Human  Right Cases	JHGD01
109	LAND ACQUISTAION CASES : LAND ACQUISTAION CASES	JHGD01
176	Letter of Administration Case : Letter of Administration Case	JHGD01
169	C II : C II	JHLH03
166	M.A.CLAIM CASES : MOTOR ACCIDENT CLAIM CASES	JHGD01
175	Misc. Civil Application : Misc. Civil Application	JHGD01
174	Misc. Criminal Application : Misc. Criminal Application	JHGD01
26	NDPS : NDPS	JHGD01
15	N.D.P.S Case : N.D.P.S Case	JHGD01
177	N.D.P.S Case  A : N.D.P.S Case A	JHGD01
178	N.D.P.S Case  B : N.D.P.S Case B	JHGD01
108	PROBATE CASE : PROBATE CASE	JHGD01
153	Regular Bail TRIAL : Regular Bail TRIAL	JHGD01
170	REVOCATION CASE : REVOCATION CASE	JHGD01
169	Revocation Cases : Revocation Cases	JHGD01
40	SC/ST CASE : SC/ST CASE	JHGD01
10	Sessions Trial : Sessions Trial	JHGD01
172	Sessions Trial A : Sessions Trial A	JHGD01
173	Sessions Trial B : Sessions Trial B	JHGD01
183	Sessions Trial C : Sessions Trial C	JHGD01
189	Sessions Trial D : Sessions Trial D	JHGD01
25	SPECIAL (POCSO) : SPECIAL (POCSO)	JHGD01
181	SPECIAL (POCSO) A : SPECIAL (POCSO) A	JHGD01
190	SPECIAL POCSOB : SPECIAL POCSOB	JHGD01
179	SPL. CASE( MSEB)A : SPL. CASE( MSEB) A	JHGD01
111	SUCCESSION CERTIFICATE CASES : SUCCESSION CERTIFICATE CASES	JHGD01
171	Transfer petition : Transfer petition	JHGD01
170	Civil Misc. Case : Civil Misc. Case	JHGD05
174	Commercial Appeals : Commercial Appeals	JHGD05
173	Commercial Suits : Commercial Suits	JHGD05
112	EXECUITION CASES : EXECUITION CASES	JHGD05
161	GUARDIANSHIP : GUARDIANSHIP	JHGD05
172	Maintenance Alteration : Maintenance Alteration	JHGD05
168	Org. Maintenance Case : Org. Maintenance Case	JHGD05
107	Original Suit : Original Suit	JHGD05
89	ADMINISTRATIVE SUITE : ADMINISTRATIVE SUITE	JHHB04
138	APP. OF RES. SUBJUDICE U/S 10 : APP. OF RES. SUBJUDICE U/S 10	JHHB04
23	ARBITRATION CASE : ARBITRATION CASE	JHHB04
47	ARBITRATION R.D. : ARBITRATION R.D.	JHHB04
158	Civil Misc. Case : Civil Misc. Case	JHHB04
48	CONTEMPT PROCEEDINGS : CONTEMPT PROCEEDINGS	JHHB04
98	CROSS SUIT : CROSS SUIT	JHHB04
90	DISSOLUTION OF PATNERSHIP : DISSOLUTION OF PATNERSHIP	JHHB04
8	ELECT. PETN : ELECT. PETN	JHHB04
171	Eviction Suit (jr.) : Eviction Suit (Jr.)	JHHB04
104	EVICTION SUIT(U/S BBC ACT/ U/S 111 TP ACT) : EVICTION SUIT(U/S BBC ACT/ U/S	JHHB04
112	Execution Cases : EXECUITION CASES	JHHB04
33	FINAL DECREE : FINAL DECREE	JHHB04
37	GUARDIAN and WARDS CASE : GUARDIAN and WARDS CASE	JHHB04
135	INJUNCTION O-39 , R1, 2 : INJUNCTION O-39 , R1, 2	JHHB04
35	INSOLVENCY : INSOLVENCY	JHHB04
97	INTERPLEADER SUIT : INTERPLEADER SUIT	JHHB04
146	Land Acquisition : Land Acquisition	JHHB04
109	LAND ACQUISTAION CASES : LAND ACQUISTAION CASES	JHHB04
5	LAND REF. : LAND REF.	JHHB04
110	LUNACY/ ADOPTION/ INSOLVANCY : LUNACY/ ADOPTION/ INSOLVANCY	JHHB04
38	MESNE PROFIT : MESNE PROFIT	JHHB04
105	MISC CASES a) O-9 , : MISC CASES	JHHB04
176	Misc. Civil Application : Misc. Civil Application	JHHB04
103	MONEY SUIT : MONEY SUIT	JHHB04
169	ORIGINAL SUIT : ORIGINAL SUIT	JHHB04
170	Partition Suit (munsif) : Partition Suit (Munsif)	JHHB04
45	PAUPER APPLICATION : PAUPER APPLICATION	JHHB04
17	REG. CIVIL SUIT : REG. CIVIL SUIT	JHHB04
42	RENT SUIT : RENT SUIT	JHHB04
120	RESTORATION OF SUITS 0-9 R-4 , 0-9 , R -9 : RESTORATION OF SUITS 0-9 R-4 ,	JHHB04
121	SETTING  ASIDE EXPARTE ORDER 0-9 - R -13 : SETTING  ASIDE EXPARTE ORDER 0	JHHB04
41	SMALL CAUSE SUIT : SMALL CAUSE SUIT	JHHB04
19	SPL.CIV. SUIT : SPL.CIV. SUIT	JHHB04
29	SPL. DARKHAST : SPL. DARKHAST	JHHB04
94	SUIT BNY PERSON OF UNSOIUND MIND : SUIT BNY PERSON OF UNSOIUND MI	JHHB04
92	SUIT BY  CORPORATION/FIRM/TRUSTEE/INDEGENT PERSON : SUIT BY  CORPORATION/FIRM/TRUS	JHHB04
93	SUIT BY MINOR : SUIT BY MINOR	JHHB04
91	SUITE BY GOVT OR PUBLIC  OFFICERS IN OFFICIAL CAPA : SUITE BY GOVT OR PUBLIC  OFFIC	JHHB04
101	SUIT FOR PERPERTUAL INJECTION : SUIT FOR PERPERTUAL INJECTION	JHHB04
99	SUIT FOR SPECIAL PERFORMANCE OF CONTRACT : SUIT FOR SPECIAL PERFORMANCE O	JHHB04
96	SUIT OF PUBLIC NAUSIANCE : SUIT OF PUBLIC NAUSIANCE	JHHB04
22	SUMMARY CASE : SUMMARY CASE	JHHB04
95	SUMMARY SUIT : SUMMARY SUIT	JHHB04
154	Title Mortgage Suit : Title Mortgage Suit	JHHB04
143	Title Suit(munsif) : Title Suit(munsif)	JHHB04
175	Title Suit (trade Mark) : TITLE SUIT (TRADE MARK)	JHHB04
89	ADMINISTRATIVE SUITE : ADMINISTRATIVE SUITE	JHHB02
138	APP. OF RES. SUBJUDICE U/S 10 : APP. OF RES. SUBJUDICE U/S 10	JHHB02
23	ARBITRATION CASE : ARBITRATION CASE	JHHB02
47	ARBITRATION R.D. : ARBITRATION R.D.	JHHB02
158	Civil Miscellaneous : Civil Miscellaneous	JHHB02
178	Commercial Appeal : Commercial Appeal	JHHB02
179	Commercial Execution Case : Commercial Execution Case	JHHB02
177	Commercial Suit : Commercial Suit	JHHB02
48	CONTEMPT PROCEEDINGS : CONTEMPT PROCEEDINGS	JHHB02
98	CROSS SUIT : CROSS SUIT	JHHB02
100	DECLARATIVCE SUIT : DECLARATIVCE SUIT	JHHB02
8	ELECT. PETN : ELECT. PETN	JHHB02
104	EVICTION SUIT(U/S BB : EVICTION SUIT	JHHB02
112	Execution Cases : EXECUITION CASES	JHHB02
170	C III : C III	JHLH03
135	INJUNCTION O-39 , R1, 2 : INJUNCTION O-39 , R1, 2	JHHB02
35	INSOLVENCY : INSOLVENCY	JHHB02
97	INTERPLEADER SUIT : INTERPLEADER SUIT	JHHB02
146	Land Acquisition : Land Acquisition	JHHB02
109	LAND ACQUISITION CASE : LAND ACQUISITION CASE	JHHB02
5	LAND REF. : LAND REF.	JHHB02
110	LUNACY/ ADOPTION/ INSOLVANCY : LUNACY/ ADOPTION/ INSOLVANCY	JHHB02
38	MESNE PROFIT : MESNE PROFIT	JHHB02
105	MISC CASES a) O-9 , : MISC CASES	JHHB02
176	Misc. Civil Application : Misc. Civil Application	JHHB02
103	MONEY SUIT : MONEY SUIT	JHHB02
143	ORIGINAL SUIT : ORIGINAL SUIT	JHHB02
102	Partition Suit (sub Judge) : PARTITION SUIT (Sub Judge)	JHHB02
45	PAUPER APPLICATION : PAUPER APPLICATION	JHHB02
42	RENT SUIT : RENT SUIT	JHHB02
120	RESTORATION OF SUITS 0-9 R-4 , 0-9 , R -9 : RESTORATION OF SUITS 0-9 R-4 ,	JHHB02
121	SETTING  ASIDE EXPARTE ORDER 0-9 - R -13 : SETTING  ASIDE EXPARTE ORDER 0	JHHB02
41	SMALL CAUSE SUIT : SMALL CAUSE SUIT	JHHB02
94	SUIT BNY PERSON OF UNSOIUND MIND : SUIT BNY PERSON OF UNSOIUND MI	JHHB02
92	SUIT BY  CORPORATION/FIRM/TRUSTEE/INDEGENT PERSON : SUIT BY  CORPORATION/FIRM/TRUS	JHHB02
93	SUIT BY MINOR : SUIT BY MINOR	JHHB02
91	SUITE BY GOVT OR PUBLIC  OFFICERS IN OFFICIAL CAPA : SUITE BY GOVT OR PUBLIC  OFFIC	JHHB02
101	SUIT FOR PERPERTUAL INJECTION : SUIT FOR PERPERTUAL INJECTION	JHHB02
99	SUIT FOR SPECIAL PERFORMANCE OF CONTRACT : SUIT FOR SPECIAL PERFORMANCE O	JHHB02
96	SUIT OF PUBLIC NAUSIANCE : SUIT OF PUBLIC NAUSIANCE	JHHB02
95	SUMMARY SUIT : SUMMARY SUIT	JHHB02
154	Title Mortgage Suit : Title Mortgage Suit	JHHB02
169	Title Suit (Sub Judge) : Title Suit (SubJudge)	JHHB02
175	Title Suit (trade Mark) : TITLE SUIT (TRADE MARK)	JHHB02
158	Civil Misc. Case : Civil Misc. Case	JHHB05
98	CROSS SUIT : CROSS SUIT	JHHB05
112	Execution Cases : EXECUITION CASES	JHHB05
161	GUARDIANSHIP CASE : GUARDIANSHIP CASE	JHHB05
176	Maint. Alt. Case : Mainenance Alteration Case	JHHB05
156	Original Maintenance : Original Maintenance Case	JHHB05
107	ORIGINAL SUIT : ORIGINAL SUIT	JHHB05
152	Anticipatory Bail : Anticipatory Bail	JHHB01
23	ARBITRATION CASE : ARBITRATION CASE	JHHB01
162	Bail Petition : Bail Petition.	JHHB01
181	Children case : Children Case	JHHB01
1	CIVIL APPEAL : CIVIL APPEAL	JHHB01
2	Civil Misc. Appeal : Civil Misc. Appeal	JHHB01
158	Civil Miscellaneous : Civil Miscellaneous	JHHB01
184	Commercial Appeal : Commercial Appeal	JHHB01
185	Commercial Execution Case : Commercial Execution Case	JHHB01
183	Commercial Suits : Commercial Suits	JHHB01
166	Complain Cases : COMPLAIN CASES	JHHB01
12	CRIMINAL APPEAL : CRIMINAL APPEAL	JHHB01
157	Criminal Misc. : Criminal Misc.	JHHB01
13	Cri. Rev Application : CRI. REV. APPPLICATION	JHHB01
182	Drug Cosmetic : Drug Cosmetic	JHHB01
168	ELECTRICITY ACT CASE : ELECTRICITY ACT CASES	JHHB01
155	Eviction Title Appeal : Eviction Title Appeal	JHHB01
112	Execution Cases : EXECUITION CASES	JHHB01
33	FINAL DECREE : FINAL DECREE	JHHB01
21	GR Case : GR CASE	JHHB01
161	GUARDIANSHIP : GUARDIANSHIP	JHHB01
35	INSOLVENCY : INSOLVENCY	JHHB01
109	LAND ACQUISTAION CASES : LAND ACQUISTAION CASES	JHHB01
5	Land Acquistion Appeal : Land Acquistion Appeal	JHHB01
179	Letter of Administration : Letter of Administration Case	JHHB01
7	L.R  DKST. : L.R  DKST.	JHHB01
178	Misc. Civil Application : Misc. Civil Application	JHHB01
177	Misc. Criminal Application : Misc. Criminal Application	JHHB01
106	Motor Accident Claims Cases : Motor Accident Claim Cases	JHHB01
15	Ndps Act Cases : NDPS ACT CASES	JHHB01
186	Original Suit : Original Suit	JHHB01
108	PROBATE CASE : PROBATE CASE	JHHB01
167	Probate Title Suit : PROBATE TITLE SUIT	JHHB01
173	SC/ ST CASE : SC/ ST CASE	JHHB01
10	SESSION TRIAL : SESSION TRIAL	JHHB01
180	Special POCSO Cases : POCSO	JHHB01
111	SUCESSION CERTIFICATE CASES : SUCESSION CERTIFICATE CASES	JHHB01
81	SUM. CIVIL SUIT : SUM. CIVIL SUIT	JHHB01
175	Title Suit (trade Mark) : TITLE SUIT (TRADE MARK)	JHHB01
43	TRUST APPEAL : TRUST APPEAL	JHHB01
44	TRUST SUIT : TRUST SUIT	JHHB01
40	VIGILANCE CASE : VIGILANCE CASE	JHHB01
148	Weight and Measurement Act : Weight and Measurement Act	JHHB01
143	Original Suit : Original Suit	JHCH02
164	Partition Suit : P.S	JHCH02
41	Small Cause Suit : SMALL CAUSE SUIT	JHCH02
159	Complaint Case : Complaint Case	JHCH03
157	Cri. Misc. Case : Cri. Misc. Case	JHCH03
16	E.C.Cases : E.C.Cases	JHCH03
21	G.R. Case : G.R. Case	JHCH03
197	Juvenile Case : Juvenile Case	JHCH03
170	Misc. Cri. Application : Misc. Cri. Application	JHCH03
179	Railway Act Case : Rail Case	JHCH03
162	U.C. Case : U.C. Case	JHCH03
142	Complaint Case : Cr. Case Complaint (P)	JHPK03
159	Complaint Case (O) : Cr. Case Complaint (O)	JHPK03
139	Complaint P Supl. : Complaint P Supl.	JHPK03
21	CRI. CASE : CRI. CASE	JHPK03
112	EXECUITION : EXECUITION	JHSK05
165	Complain : Complaint Cases	JHLH03
181	complain splitup case : complain splitup case	JHLH03
157	Crl. Misc Case : Criminal Mislaneous Case	JHLH03
164	Forest : Forest Cases	JHLH03
163	G.R. : General Register	JHLH03
183	G R Split : G R Split	JHLH03
182	G. R Split 5 : G. R. Split 5	JHLH03
172	G.R. Suppl. : G.R. Supplementary	JHLH03
173	G.R. Suppl 1 : G.R. Supplementary File 1	JHLH03
174	G.R. Suppl. 2 : G.R. Supplementary 2	JHLH03
175	G.R. Suppl. 3 : G.R. Supplementary 3	JHLH03
176	Misc : Misc	JHLH03
180	Misc Criminal Application : Misc Criminal Application	JHLH03
168	M.V. : Motor Vehicle	JHLH03
173	Civil Misc. Case : Civil Misc. Case	JHLH04
8	Elec. Petition : ELECTION PETITION	JHLH04
104	Eviction Suit : EVICTION SUIT	JHLH04
112	EXECUITION CASES : EXECUITION CASES	JHLH04
105	Misc Cases : MISC CASES	JHLH04
175	Misc Civil Application : Misc Civil Application	JHLH04
158	Miscellaneous : Miscellaneous	JHLH04
171	Misc (transfer) Cases : Misc (Transfer) Cases	JHLH04
103	M. S. : MONEY SUIT	JHLH04
174	Original Suit : Original Suit	JHLH04
102	Partition Suit : PARTITION SUIT	JHLH04
143	T . S. : TITLE SUIT	JHLH04
2	Civil Misc. Case : Civil MIsc. Case	JHLH02
174	Commercial(Arbitration) case : Commercial(Arbitration) case	JHLH02
176	Commercial Execution : Commercial Execution	JHLH02
175	Commercial Revocation : Commercial Revocation	JHLH02
177	Commercial (Suit) case : Commercial (Suit) case	JHLH02
104	Eviction Suit : EVICTION SUIT	JHLH02
112	EXECUITION CASES : EXECUITION CASES	JHLH02
135	INJUNCTION : INJUNCTION	JHLH02
146	L.a. : LAND ACQUISITION	JHLH02
109	LAND ACQUISTAION CASES : LAND ACQUISTAION CASES	JHLH02
105	Misc Cases : MISC CASES	JHLH02
173	Misc Civil Application : Misc Civil Application	JHLH02
158	Miscellaneous : Miscellaneous	JHLH02
103	M. S. : MONEY SUIT	JHLH02
172	Original Suit : Original Suit	JHLH02
102	Partition Suit : PARTITION SUIT	JHLH02
143	T . S. : TITLE SUIT	JHLH02
158	Civil Misc. : Civil Miscellaneous	JHLH05
112	EXEC. : EXECUITION CASES	JHLH05
161	GUARD. : GUARDIANSHIP	JHLH05
156	Maintainance : Maintainance	JHLH05
178	Maintenance Alteration case : Maintenance Alteration case	JHLH05
105	Misc Cases : MISC CASES	JHLH05
177	Misc Civil Application : Misc Civil Application	JHLH05
176	Misc Criminal Application : Misc Criminal Application	JHLH05
107	M.T.S. : MATRIMONIAL CASE	JHLH05
174	O.M.C : Original Maintenance Case	JHLH05
173	Original Suit : Original Suit	JHLH05
152	A.B.P. : Anticipatory Bail PETITION	JHLH01
23	ARBITRATION CASE : ARBITRATION CASE	JHLH01
24	B.P. : BAIL PETITION	JHLH01
181	Children Case : Children Case	JHLH01
175	Civil Appeal : Civil Appeal	JHLH01
2	CIVIL MISC  APPEAL : CIVIL MISC APPEAL	JHLH01
188	Commercial Appeal : Commercial Appeal	JHLH01
190	Commercial(Arbitration) : Commercial(Arbitration)	JHLH01
191	Commercial Execution case : Commercial Execution case	JHLH01
192	Commercial Revocation Case : Commercial Revocation Case	JHLH01
187	Commercial (Suit)  case : Commercial (Suit) case	JHLH01
167	Compensation : Compensation cases	JHLH01
165	Complain Case : Complaint Cases	JHLH01
12	Cr. Appeal : CRIMINAL APPEAL	JHLH01
157	Criminal Miscellaneous : Criminal Miscellaneous	JHLH01
13	Cr. Revision : CRIMINAL REVISION	JHLH01
186	Drug and Cosmetic : Drug and Cosmetic	JHLH01
16	Electricity Act Case : Electricity Act Case	JHLH01
112	EXECUITION CASES : EXECUITION CASES	JHLH01
163	G . R . : General Register	JHLH01
161	GUARDIANSHIP : GUARDIANSHIP	JHLH01
178	M.A.C.C. : Motor Accident  Claims Cases	JHLH01
158	Misc. Case MACT : Misc. Case MACT	JHLH01
105	Misc Cases : MISC  CASES	JHLH01
182	MISC.CIVIL : Misc.Civil	JHLH01
184	Misc Civil Application : Misc Civil Application	JHLH01
172	Misc Comp. : Miscellaneous Comp	JHLH01
183	Misc Criminal Application : Misc Criminal Application	JHLH01
140	MONEY APPEAL : MONEY APPEAL	JHLH01
15	NDPS CASE : NDPS CASE	JHLH01
177	Original Suit : Original Suit	JHLH01
108	PROBATE CASE : PROBATE CASE	JHLH01
185	REVOCATION CASE : REVOCATION CASE	JHLH01
180	SC/ST Case : SC/ST Cases	JHLH01
193	Special NIA : Special NIA	JHLH01
179	Special POCSO Case : Special POCSO Case	JHLH01
162	S.T. : Session Trial	JHLH01
173	S.T. Split : S.T. Split up File	JHLH01
111	Sucession Certificate Cases : SUCESSION CERTIFICATE CASES	JHLH01
1	TITLE APPEAL : TITLE APPEAL	JHLH01
176	Transfer Petition : Transfer Petition	JHLH01
165	Civil Misc. Case : Civil Misc. Case	JHCH05
166	Maintenance Alt. Case : Maintenance Alteration Case	JHCH05
168	Misc. Civil Application : Misc. Civil Application	JHCH05
167	Misc. Cri. Application : Misc. Cri. Application	JHCH05
107	Original Suit : Original Suit	JHCH05
179	C l a : Contract Labour Act	JHDH03
178	C m a : Coal Mines Act	JHDH03
172	C o cases : C O Cases	JHDH03
173	Complaint Case : Complaint Case	JHDH03
207	Compliant  By Officer Eco offe : C O ECO Off	JHDH03
168	Cr ex case : Cr Execution Case	JHDH03
24	CRI  BAIL APPLN : CRI BAIL APPLN	JHDH03
21	CRI CASE : CRI CASE	JHDH03
157	Cri  misc case : Criminal Misc CASE	JHDH03
212	CRIMNAL REVISION : CRIMNAL REVISION	JHDH03
80	DISTRESS WARRENT : DISTRESS WARRENT	JHDH03
147	Domestic Violence Act 2005 : Domestic Violence Act 2005	JHDH03
192	Employement Exchange : Employement Exchange	JHDH03
188	E p f act : Employees Provident Fund	JHDH03
187	E r act Case : Equal Remuneration Act Case	JHDH03
32	E S I  ACT  CASE : E S I ACT CASE	JHDH03
149	Excise Act : Excise Act	JHDH03
177	F a : Factory Act	JHDH03
204	FSSA Act-2006 : Food Safety and standard Act	JHDH03
164	GR : General Register No	JHDH03
214	G. R. Addl Railway : G. R. Addl Railway	JHDH03
215	G.R Dhanbad Railway : G.R Dhanbad Railway	JHDH03
191	I d Act : Indutrial Disputes Act	JHDH03
202	Information Request Case : Information Request Case	JHDH03
201	J S and Estbl Act 1953 : Jharkhand Shop and Estab Act	JHDH03
213	Juvenile Cases : Juvenile Cases	JHDH03
210	Misc Criminal Application : Misc Criminal Application	JHDH03
184	Misc Petition : Misc Petition	JHDH03
190	M w Case : Minimum Wages Act	JHDH03
198	Non F I R : Non F I R	JHDH03
30	OTHER MISC CRI  APPLN : OTHER MISC CRI APPLN	JHDH03
197	Payment Of Bonus : Payment Of Bonus	JHDH03
180	P C A : Prevension of Cruelty Against	JHDH03
189	P f a Act : PREVENTATION OF FOOD ADULTRATI	JHDH03
196	P G Act : Payment of Gratuty Act	JHDH03
186	P w Case : Payment Of  Wage Act	JHDH03
217	Railway Property Addl Railway : Railway Property Dhanbad Railway	JHDH03
181	Railway Property Dhn Railway : Railway Property Dhn Railway	JHDH03
20	REG  CRI CASE : REG CRI CASE	JHDH03
153	Regular Bail : Regular Bail	JHDH03
28	REGULAR PETITION : REGULAR PETITION	JHDH03
31	REVIEW APPLICATION : REVIEW APPLICATION	JHDH03
10	Session Trial : SESSION TRIAL	JHDH03
206	Spl sc st  Case No : Spl SC ST Case no	JHDH03
216	Under Railway Act Addl  Railway : Under Railway Act Addl Railway	JHDH03
182	Under Railway Act Dhn Railway : Under Railway Act Dhn Railway	JHDH03
193	Vig Case : Vigilance P S Case	JHDH03
160	Weight amp measurement Act 1985 : Weight ampmeasurement Act 1985	JHDH03
148	Weight and  Measurement Act 1985 : Weight and Measurement Act	JHDH03
89	ADMINISTRATIVE SUITE : ADMINISTRATIVE SUITE	JHDH04
23	ARBITRATION CASE : ARBITRATION CASE	JHDH04
47	ARBITRATION RD : ARBITRATION RD	JHDH04
169	Civil Ex Case : civil execution case	JHDH04
212	CIVIL MISC APPEAL : CIVIL MISC APPEAL	JHDH04
170	Civil Misc Case : civil misc. case	JHDH04
48	CONTEMPT PROCEEDINGS : CONTEMPT PROCEEDINGS	JHDH04
98	CROSS SUIT : CROSS SUIT	JHDH04
100	DECLARATIVCE SUIT : DECLARATIVCE SUIT	JHDH04
90	DISSOLUTION OF PATNERSHIP : DISSOLUTION OF PATNERSHIP	JHDH04
8	ELECT PETN : ELECT PETN	JHDH04
104	EVICTION SUIT(U/S BBC ACT/ U/S 111 TP ACT) : EVICTION SUIT(U/S BBC ACT/ U/S	JHDH04
112	EXECUTION CASES : EXECUTION CASES	JHDH04
33	FINAL DECREE : FINAL DECREE	JHDH04
37	GUARDIAN  WARDS CASE : GUARDIAN  WARDS CASE	JHDH04
35	INSOLVENCY : INSOLVENCY	JHDH04
97	INTERPLEADER SUIT : INTERPLEADER SUIT	JHDH04
109	LAND ACQUISTAION CASES : LAND ACQUISTAION CASES	JHDH04
5	LAND REF : LAND REF	JHDH04
146	L A REF : L A REF	JHDH04
105	MISC CASES a) O-9 , R-4  b) O-9, R-9 c) U/S 47CPC : MISC CASES a) O-9 , R-4  b) O-	JHDH04
210	Misc Civil Application : Misc Civil Application	JHDH04
158	Miscelleneous : Miscelleneous	JHDH04
103	MONEY SUIT : MONEY SUIT	JHDH04
171	Ms. Petition : misc. petition	JHDH04
208	ORIGINAL SUIT : ORIGINAL SUIT	JHDH04
102	PARTION SUIT  TITLE PARTION SUIT : PARTION SUIT  TITLE PARTION S	JHDH04
45	PAUPER APPLICATION : PAUPER APPLICATION	JHDH04
186	P.w. Case : Payment Of  Wage Act	JHDH04
42	RENT SUIT : RENT SUIT	JHDH04
41	SMALL CAUSE SUIT : SMALL CAUSE SUIT	JHDH04
19	SPL CIV SUIT : SPL CIV SUIT	JHDH04
92	SUIT BY  CORPORATION/FIRM/TRUSTEE/INDEGENT PERSON : SUIT BY  CORPORATION/FIRM/TRUS	JHDH04
93	SUIT BY MINOR : SUIT BY MINOR	JHDH04
91	SUITE BY GOVT OR PUBLIC  OFFICERS IN OFFICIAL CAPA : SUITE BY GOVT OR PUBLIC  OFFIC	JHDH04
101	SUIT FOR PERPERTUAL INJECTION : SUIT FOR PERPERTUAL INJECTION	JHDH04
99	SUIT FOR SPECIAL PERFORMANCE OF CONTRACT : SUIT FOR SPECIAL PERFORMANCE O	JHDH04
96	SUIT OF PUBLIC NAUSIANCE : SUIT OF PUBLIC NAUSIANCE	JHDH04
22	SUMMARY CASE : SUMMARY CASE	JHDH04
95	SUMMARY SUIT : SUMMARY SUIT	JHDH04
175	Title Arbitration Suit : Title Arbitration Suit	JHDH04
155	Title Eviction Suit : Title Eviction Suit	JHDH04
174	Title Partition suit : Title Partition suit	JHDH04
143	Title Suit : Title Suit	JHDH04
219	ACQUITTAL APPEAL : ACQUITTAL APPEAL	JHDH02
175	C.1 : C.1	JHJR09
89	ADMINISTRATIVE SUITE : ADMINISTRATIVE SUITE	JHDH02
152	Anti b p : Anti B P	JHDH02
138	APP OF RES SUBJUDICE U/S 10 : APP  OF RES SUBJUDICE U/S 10	JHDH02
23	ARBITRATION CASE : ARBITRATION CASE	JHDH02
47	ARBITRATION R D : ARBITRATION R D	JHDH02
165	B p : BAIL PETITION	JHDH02
9	C APPNL : C APPNL	JHDH02
1	CIVIL APPEAL : CIVIL APPEAL	JHDH02
169	Civil Ex Case : civil execution case	JHDH02
224	CIVIL MISC APPEAL : CIVIL MISC APPEAL	JHDH02
170	Civil Misc  Case : civil misc case	JHDH02
172	C.o.cases : C.O.Cases	JHDH02
223	COMMERCIAL APPEAL : COMMERCIAL APPEAL	JHDH02
226	COMMERCIAL ARBITRATION : COMMERCIAL ARBITRATION	JHDH02
227	COMMERCIAL CIVIL MISC CASE : COMMERCIAL CIVIL MISC CASE	JHDH02
225	COMMERCIAL EXECUTION : COMMERCIAL EXECUTION	JHDH02
222	COMMERCIAL SUIT : COMMERCIAL SUIT	JHDH02
207	Compliant  By Officer Eco offe : C O (ECO Off)	JHDH02
48	CONTEMPT PROCEEDINGS : CONTEMPT PROCEEDINGS	JHDH02
173	C p : Complaint Case	JHDH02
142	Cr  Case Complaint P : Cr  Case Complaint P	JHDH02
12	CRI  APPEAL : CRI  APPEAL	JHDH02
24	CRI BAIL APPLN : CRI BAIL APPLN	JHDH02
21	CRI CASE : CRI  CASE	JHDH02
157	Cri misc case : Criminal Misc CASE	JHDH02
13	Cri  Rev : CRI REVISION	JHDH02
98	CROSS SUIT : CROSS SUIT	JHDH02
221	CYBER CASE : CYBER CASE	JHDH02
100	DECLARATIVCE SUIT : DECLARATIVCE SUIT	JHDH02
90	DISSOLUTION OF PATNERSHIP : DISSOLUTION OF PATNERSHIP	JHDH02
80	DISTRESS WARRENT : DISTRESS WARRENT	JHDH02
147	Domestic Violence Act 2005 : Domestic Violence Act 2005	JHDH02
220	DRUG AND COSMETICS CASE : DRUG AND COSMETIC CASE	JHDH02
8	ELECT ION PETITION : ELECTION  PETITION	JHDH02
162	ELECTRICITY ACT CASE : ELECTRICITY ACT CASE	JHDH02
187	E.r.act Case : Equal Remuneration Act Case	JHDH02
149	Excise Act : Excise Act	JHDH02
112	EXECUTION CASES : EXECUTION CASES	JHDH02
33	FINAL DECREE : FINAL DECREE	JHDH02
164	G r : General Register No	JHDH02
161	GUARDIANSHIP : GUARDIANSHIP	JHDH02
215	HUMAN RIGHT CASE : HUMAN RIGHT CASE	JHDH02
135	INJUNCTION O-39  R1 2 : INJUNCTION O-39  R1 2	JHDH02
35	INSOLVENCY : INSOLVENCY	JHDH02
97	INTERPLEADER SUIT : INTERPLEADER SUIT	JHDH02
212	Land Acqisition Misc Case : Land Acqisition Misc Case	JHDH02
216	LAND ACQUISITION APPEAL : LAND ACQUISITION APPEAL	JHDH02
211	Land Acquisition Execution Case : Land Acquisition Execution Cas	JHDH02
109	LAND ACQUISTAION CASES : LAND ACQUISTAION CASES	JHDH02
146	L a ref : L A Ref	JHDH02
195	Letter Of Admn : Letter Of Administration	JHDH02
110	LUNACY ADOPTION  INSOLVANCY : LUNACY  ADOPTION  INSOLVANCY	JHDH02
156	Maintainance : Maintainance	JHDH02
38	MESNE PROFIT : MESNE PROFIT	JHDH02
105	MISC CASES a) O-9 : MISC CASES a) O-9  R-4  b) O-	JHDH02
213	Misc Civil Application : Misc Civil Application	JHDH02
217	MISC CRIMINAL APPLICATION : MISC CRIMINAL APPLICATION	JHDH02
218	MISCELLENOUS CIVIL APPLICATION : MISCELLENOUS CIVIL APPLICATION	JHDH02
183	Misc non judl cases : Misc Non Judicial Cases	JHDH02
184	Misc Petition : Misc Petition	JHDH02
158	Mislaneous : Mislaneous	JHDH02
103	MONEY SUIT : MONEY SUIT	JHDH02
106	Motor Accident Claim Case : Motor Accident Claim Case	JHDH02
171	Ms  Petition : misc  petition	JHDH02
166	NDPS : NDPS	JHDH02
198	Non F I R : Non F I R	JHDH02
209	ORIGINAL SUIT : ORIGINAL SUIT	JHDH02
208	ORIG SUIT : ORIG SUIT	JHDH02
102	PARTION SUIT  TITLE PARTION SUIT : PARTION SUIT  TITLE PARTION S	JHDH02
45	PAUPER APPLICATION : PAUPER APPLICATION	JHDH02
180	P.C.A : Prevension of Cruelty Against	JHDH02
36	PROBATE : PROBATE	JHDH02
186	P.w. Case : Payment Of  Wage Act	JHDH02
17	REG  CIVIL SUIT : REG  CIVIL SUIT	JHDH02
20	REG CRI  CASE : REG  CRI  CASE	JHDH02
153	Regular Bail : Regular Bail	JHDH02
42	RENT SUIT : RENT SUIT	JHDH02
120	RESTORATION OF SUITS 0-9 R-4  0-9 , R -9 : RESTORATION OF SUITS 0-9 R-4	JHDH02
10	Session Trial : SESSION TRIAL	JHDH02
121	SETTING  ASIDE EXPARTE ORDER 0-9 - R -13 : SETTING  ASIDE EXPARTE ORDER 0	JHDH02
34	SMALL CASES : SMALL CASES	JHDH02
19	SPL CIV  SUIT : SPL CIV  SUIT	JHDH02
205	Spl Pocso Act : Spl Pocso Case	JHDH02
206	Spl(sc/st) Case No : Spl(SC/ST) Case no	JHDH02
167	Spl.(vig) Case : SPECIAL VIGILANCE CASE	JHDH02
210	Succession Case : Succession Case	JHDH02
111	SUCESSION CERTIFICATE CASES : SUCESSION CERTIFICATE CASES	JHDH02
94	SUIT BNY PERSON OF UNSOIUND MIND : SUIT BNY PERSON OF UNSOIUND MI	JHDH02
92	SUIT BY  CORPORATION FIRM TRUSTEE INDEGENT PERSON : SUIT BY  CORPORATION FIRM TRUS	JHDH02
93	SUIT BY MINOR : SUIT BY MINOR	JHDH02
91	SUITE BY GOVT OR PUBLIC  OFFICERS IN OFFICIAL CAPA : SUITE BY GOVT OR PUBLIC  OFFIC	JHDH02
101	SUIT FOR PERPERTUAL INJECTION : SUIT FOR PERPERTUAL INJECTION	JHDH02
99	SUIT FOR SPECIAL PERFORMANCE OF CONTRACT : SUIT FOR SPECIAL PERFORMANCE O	JHDH02
96	SUIT OF PUBLIC NAUSIANCE : SUIT OF PUBLIC NAUSIANCE	JHDH02
196	C/1.s : C/1.s	JHJR09
81	SUM  CIVIL SUIT : SUM  CIVIL SUIT	JHDH02
95	SUMMARY SUIT : SUMMARY SUIT	JHDH02
175	Title a suit : Title Arbitration Suit	JHDH02
155	Title Eviction Suit : Title Eviction Suit	JHDH02
154	Title Mortgage Suit : Title Mortgage Suit	JHDH02
143	Title Suit : Title Suit	JHDH02
200	T N tm S : Title Trade Mark  Suit	JHDH02
174	T p suit : Title partion Suit	JHDH02
214	TRANSFER PETITION CRIMINAL CASE : TRANSFER PETITION CRIMINAL CASE	JHDH02
43	TRUST APPEAL : TRUST APPEAL	JHDH02
44	TRUST SUIT : TRUST SUIT	JHDH02
193	Vig Case : Vigilance P.S Case	JHDH02
148	Weight and Measurement Act : Weight and Measurement Act	JHDH02
219	Acquittal Appeal : Acquittal Appeal	JHDH01
152	Anticipatory Bail Petition : Anticipatory Bail Petition	JHDH01
23	Arbitration case : Arbitration case	JHDH01
165	Bail Petition : Bail Petition	JHDH01
224	Children Case : Children case	JHDH01
1	CIVIL  APPEAL : CIVIL APPEAL	JHDH01
169	Civil Ex Case : civil execution case	JHDH01
2	Civil Misc Appeal : Civil Misc  Appeal	JHDH01
170	Civil Misc  Case : civil misc case	JHDH01
223	Commercial Appeal : Commercial Appeal	JHDH01
228	COMMERCIAL ARBITRATION : COMMERCIAL ARBITRATION	JHDH01
229	COMMERCIAL CIVIL MISC CASE : COMMERCIAL CIVIL MISC CASE	JHDH01
227	COMMERCIAL EXECUTION : COMMERCIAL EXECUTION	JHDH01
222	Commercial Suit : Commercial Suit	JHDH01
173	COMPLAINT CASE : Complaint Case	JHDH01
12	Criminal Appeal : Criminal Appeal	JHDH01
168	Criminal Execution Case : Cr.Execution Case	JHDH01
157	Criminal Misc Case : Criminal Misc CASE	JHDH01
13	Criminal Revision : Criminal Revision	JHDH01
221	CYBER CASE : CYBER CASE	JHDH01
220	Drug and Cosmetics Case : Drug and Cosmetics Case	JHDH01
162	Electricity Act Case : Electricity Act Case	JHDH01
112	Execution Case : EXECUTION CASES	JHDH01
164	GR : GR	JHDH01
37	Guardian and Wards Case : Guardian and Wards Case	JHDH01
161	Guardianship Case : GUARDIANSHIP CASE	JHDH01
215	Human Right Cases : Human Right Case	JHDH01
191	Indutrial Disputes Act : Indutrial Disputes Act	JHDH01
210	Information Appeal : Information Appeal	JHDH01
226	INSOLVENCY CASE : INSOLVENCY CASE	JHDH01
216	Land Acquisition Appeal : Land Acquisition Appeal	JHDH01
195	Letter of Administration Case : Letter Of Administration cases	JHDH01
105	MISC CASES a) O-9 , R-4  b) O-9, R-9 c) U/S 47CPC : MISC CASES a) O-9 , R-4  b) O-	JHDH01
218	Misc Civil Application : Misc Civil Application	JHDH01
217	Misc Criminal Application : Misc Criminal Application	JHDH01
158	Miscellaneous Petition : Miscellaneous Petition	JHDH01
183	Misc Non Judicial cases : Misc Non Judicial Cases	JHDH01
106	Motor Accident Claims Cases : Motor Accident Claims Cases	JHDH01
190	M.w Case : Minimum Wages Act	JHDH01
166	NDPS Case : NDPS case	JHDH01
212	Original Suit : ORIGINAL SUIT	JHDH01
108	Probate Case : PROBATE CASE	JHDH01
20	Regular CBI Cases : Regular CBI Cases	JHDH01
206	SC ST Case : SC ST Case	JHDH01
10	Session Trial : SESSION TRIAL	JHDH01
205	Spl POCSO Case : Spl Pocso Case	JHDH01
167	Spl vig Case : SPECIAL VIGILANCE CASE	JHDH01
213	Transfer Petition : Transfer Petition	JHDH01
214	Transfer Petition Criminal Case : Transfer Petition Criminal Cas	JHDH01
170	Civil Misc Case : civil misc. case	JHDH05
157	Criminal Misc Case : Criminal Misc Case	JHDH05
161	Guardianship : Guardianship	JHDH05
156	Maintainance : Maintainance	JHDH05
210	Maintainance Alteration Case : Maintainance Alteration Case	JHDH05
107	MATRIMONIAL CASE : MATRIMONIAL CASE	JHDH05
209	Original Maintainance Case : Original Maintainance Case	JHDH05
208	Original Suit : Original Suit	JHDH05
173	Complaint Case : Complaint Case	JHGR03
130	COMPROMISE PETITIONER 0-23 R-3 : COMPROMISE PETITIONER 0-23 R-3	JHGR03
159	Cr. Case Complaint (O) : Cr. Case Complaint (O)	JHGR03
142	Cr. Case Complaint (P) : Cr. Case Complaint (P)	JHGR03
168	Cr.ex.case : Cr.Execution Case	JHGR03
24	CRI. BAIL APPLN. : CRI. BAIL APPLN.	JHGR03
21	CRI. CASE : CRI. CASE	JHGR03
157	Crl. Misc. Case : Crl. Misc. Case	JHGR03
80	DISTRESS WARRENT : DISTRESS WARRENT	JHGR03
147	Domestic Violence Act 2005 : Domestic Violence Act 2005	JHGR03
192	Employement Exchange : Employement Exchange	JHGR03
188	E.p.f.act : Employees Provident Fund	JHGR03
32	E.S.I. ACT  CASE : E.S.I. ACT  CASE	JHGR03
149	Excise Act : Excise Act	JHGR03
177	F.a : Factory Act	JHGR03
176	Forest Case : Bihar Forest Act	JHGR03
164	G.R. Cases : General Register No.	JHGR03
199	MISC CRI APP : MISC. CRI. APPLICATION	JHGR03
184	Misc. Petition : Misc, petition	JHGR03
190	M.w Case : Minimum Wages Act	JHGR03
198	Non F I R : Non F I R	JHGR03
30	OTHER MISC. CRI. APPLN. : OTHER MISC. CRI. APPLN.	JHGR03
189	P.f.a Act : PREVENTATION OF FOOD ADULTRATI	JHGR03
181	Railway Property : Unlawful Possession Act	JHGR03
20	REG. CRI. CASE : REG. CRI. CASE	JHGR03
153	Regular Bail : Regular Bail	JHGR03
31	REVIEW APPLICATION : REVIEW APPLICATION	JHGR03
25	SPL. CRI. MA : SPL. CRI. MA	JHGR03
182	Under Railway Act : Under Railway Act	JHGR03
193	Vig Case : Vigilance P.S Case	JHGR03
160	Weight and amp measurement Act 1985 : Weight ampmeasurement Act 1985	JHGR03
148	Weight and Measurement Act : Weight and Measurement Act	JHGR03
89	ADMINISTRATIVE SUITE : ADMINISTRATIVE SUITE	JHGR04
138	APP. OF RES. SUBJUDICE U/S 10 : APP. OF RES. SUBJUDICE U/S 10	JHGR04
23	ARBITRATION CASE : ARBITRATION CASE	JHGR04
47	ARBITRATION R.D. : ARBITRATION R.D.	JHGR04
169	Civil Ex Case : civil execution case	JHGR04
170	Civil Misc. Case : civil misc. case	JHGR04
48	CONTEMPT PROCEEDINGS : CONTEMPT PROCEEDINGS	JHGR04
98	CROSS SUIT : CROSS SUIT	JHGR04
100	DECLARATIVCE SUIT : DECLARATIVCE SUIT	JHGR04
90	DISSOLUTION OF PATNERSHIP : DISSOLUTION OF PATNERSHIP	JHGR04
8	ELECT. PETN : ELECT. PETN	JHGR04
104	EVICTION SUIT : EVICTION SUIT	JHGR04
112	EXECUTION CASE : EXECUITION CASES	JHGR04
37	GUARDIAN AND WARDS CASE : GUARDIAN AND WARDS CASE	JHGR04
35	INSOLVENCY : INSOLVENCY	JHGR04
97	INTERPLEADER SUIT : INTERPLEADER SUIT	JHGR04
109	LAND ACQUISTAION CASES : LAND ACQUISTAION CASES	JHGR04
5	LAND REF. : LAND REF.	JHGR04
146	L.a.ref : L.A.Ref	JHGR04
110	LUNACY/ ADOPTION/ INSOLVANCY : LUNACY/ ADOPTION/ INSOLVANCY	JHGR04
38	MESNE PROFIT : MESNE PROFIT	JHGR04
105	MISC CASES a) O-9 , R-4  b) O-9, R-9 c) U/S 47CPC : MISC CASES a) O-9 , R-4  b) O-	JHGR04
199	MISC CIV APP : MISC. CIVIL APPLICATION	JHGR04
158	Mislaneous : Mislaneous	JHGR04
103	MONEY SUIT : MONEY SUIT	JHGR04
171	Ms. Petition : misc. petition	JHGR04
143	Original Suit : Title Suit/Partition Suit	JHGR04
102	PARTITION SUIT : PARTITION SUIT	JHGR04
45	PAUPER APPLICATION : PAUPER APPLICATION	JHGR04
108	PROBATE CASE : PROBATE CASE	JHGR04
17	REG. CIVIL SUIT : REG. CIVIL SUIT	JHGR04
28	REGULAR PETITION : REGULAR PETITION	JHGR04
42	RENT SUIT : RENT SUIT	JHGR04
120	RESTORATION OF SUITS 0-9 R-4 , 0-9 , R -9 : RESTORATION OF SUITS 0-9 R-4 ,	JHGR04
121	SETTING  ASIDE EXPARTE ORDER 0-9 - R -13 : SETTING  ASIDE EXPARTE ORDER 0	JHGR04
41	SMALL CAUSE SUIT : SMALL CAUSE SUIT	JHGR04
19	SPL.CIV. SUIT : SPL.CIV. SUIT	JHGR04
29	SPL. DARKHAST : SPL. DARKHAST	JHGR04
94	SUIT BNY PERSON OF UNSOIUND MIND : SUIT BNY PERSON OF UNSOIUND MI	JHGR04
92	SUIT BY  CORPORATION/FIRM/TRUSTEE/INDEGENT PERSON : SUIT BY  CORPORATION/FIRM/TRUS	JHGR04
93	SUIT BY MINOR : SUIT BY MINOR	JHGR04
91	SUITE BY GOVT OR PUBLIC  OFFICERS IN OFFICIAL CAPA : SUITE BY GOVT OR PUBLIC  OFFIC	JHGR04
101	SUIT FOR PERPERTUAL INJECTION : SUIT FOR PERPERTUAL INJECTION	JHGR04
96	SUIT OF PUBLIC NAUSIANCE : SUIT OF PUBLIC NAUSIANCE	JHGR04
22	SUMMARY CASE : SUMMARY CASE	JHGR04
95	SUMMARY SUIT : SUMMARY SUIT	JHGR04
175	Title (a)suit : Title Arbitration Suit	JHGR04
154	Title Mortgage Suit : Title Mortgage Suit	JHGR04
174	T(p)suit : Title(partion)Suit	JHGR04
89	ADMINISTRATIVE SUITE : ADMINISTRATIVE SUITE	JHGR02
138	APP. OF RES. SUBJUDICE U/S 10 : APP. OF RES. SUBJUDICE U/S 10	JHGR02
23	ARBITRATION CASE : ARBITRATION CASE	JHGR02
170	Civil Misc. Case : civil misc. case	JHGR02
48	CONTEMPT PROCEEDINGS : CONTEMPT PROCEEDINGS	JHGR02
98	CROSS SUIT : CROSS SUIT	JHGR02
100	DECLARATIVCE SUIT : DECLARATIVCE SUIT	JHGR02
90	DISSOLUTION OF PATNERSHIP : DISSOLUTION OF PATNERSHIP	JHGR02
8	ELECT. PETN : ELECT. PETN	JHGR02
104	EVICTION SUIT(U/S BBC ACT/ U/S 111 TP ACT) : EVICTION SUIT(U/S BBC ACT/ U/S	JHGR02
112	EXECUTION CASES : EXECUTION CASES	JHGR02
135	INJUNCTION O-39 , R1, 2 : INJUNCTION O-39 , R1, 2	JHGR02
35	INSOLVENCY : INSOLVENCY	JHGR02
97	INTERPLEADER SUIT : INTERPLEADER SUIT	JHGR02
109	LAND ACQUISITON CASES : LAND ACQUISITION CASES	JHGR02
110	LUNACY/ ADOPTION/ INSOLVANCY : LUNACY/ ADOPTION/ INSOLVANCY	JHGR02
38	MESNE PROFIT : MESNE PROFIT	JHGR02
105	MISC CASES a) O-9 , R-4  b) O-9, R-9 c) U/S 47CPC : MISC CASES a) O-9 , R-4  b) O-	JHGR02
199	MISC CIV APP : MISC. CIVIL APPLICATION	JHGR02
183	Misc(non-judl.)cases : Misc(Non-Judicial)Cases	JHGR02
184	Misc Petition : Misc Petition	JHGR02
158	Mislaneous : Mislaneous	JHGR02
103	MONEY SUIT : MONEY SUIT	JHGR02
143	ORIGINAL SUIT : ORIGINAL SUIT	JHGR02
174	Original (Title) Suit : Title(partion)Suit	JHGR02
102	PARTITION SUIT : PARTITION SUIT	JHGR02
45	PAUPER APPLICATION : PAUPER APPLICATION	JHGR02
17	REG. CIVIL SUIT : REG. CIVIL SUIT	JHGR02
120	RESTORATION OF SUITS 0-9 R-4 , 0-9 , R -9 : RESTORATION OF SUITS 0-9 R-4 ,	JHGR02
121	SETTING  ASIDE EXPARTE ORDER 0-9 - R -13 : SETTING  ASIDE EXPARTE ORDER 0	JHGR02
41	SMALL CAUSE SUIT : SMALL CAUSE SUIT	JHGR02
19	SPL.CIV. SUIT : SPL.CIV. SUIT	JHGR02
94	SUIT BNY PERSON OF UNSOIUND MIND : SUIT BNY PERSON OF UNSOIUND MI	JHGR02
92	SUIT BY  CORPORATION/FIRM/TRUSTEE/INDEGENT PERSON : SUIT BY  CORPORATION/FIRM/TRUS	JHGR02
93	SUIT BY MINOR : SUIT BY MINOR	JHGR02
91	SUITE BY GOVT OR PUBLIC  OFFICERS IN OFFICIAL CAPA : SUITE BY GOVT OR PUBLIC  OFFIC	JHGR02
101	SUIT FOR PERPERTUAL INJECTION : SUIT FOR PERPERTUAL INJECTION	JHGR02
99	SUIT FOR SPECIAL PERFORMANCE OF CONTRACT : SUIT FOR SPECIAL PERFORMANCE O	JHGR02
96	SUIT OF PUBLIC NAUSIANCE : SUIT OF PUBLIC NAUSIANCE	JHGR02
175	Title (a)suit : Title Arbitration Suit	JHGR02
154	Title Mortgage Suit : Title Mortgage Suit	JHGR02
200	TRANSFER PETITION : TRANSFER PETITION	JHGR02
152	Anticipatory Bail Petition : Anti.B.P.	JHGR01
23	ARBITRATION CASE : ARBITRATION CASE	JHGR01
165	Bail Petition : BAIL PETITION	JHGR01
207	Children Case : Children Case	JHGR01
1	Civil Appeal : TITLE APPEAL	JHGR01
2	Civil Misc. Appeal : Civil Misc.  Appeal	JHGR01
170	Civil Misc. Case : Civil Misc Case	JHGR01
102	CIVIL (TITLE/PARTITION) APPEAL/SUIT : PARTION SUIT / TITLE PARTION S	JHGR01
212	Commercial Appeal : Commercial Appeal	JHGR01
211	Commercial Suit : Commercial Suit	JHGR01
173	Complaint Case : Complaint Case	JHGR01
210	Confiscation Appeal : Confiscation Appeal	JHGR01
12	Criminal Appeal : CRI. APPEAL	JHGR01
157	Criminal Misc. : Criminal Misc.	JHGR01
13	Criminal Revision : CRI. REVISION	JHGR01
208	CYBER CASE : CYBER CASE	JHGR01
209	Drug Cosmetic Case : Drug Cosmetic Case	JHGR01
16	ELECTRICITY ACT SPL. CASES : E.C.ACT.SPL.CASE	JHGR01
199	EVICTION APPEAL : EVICTION APPEAL	JHGR01
104	EVICTION SUIT : EVICTION SUIT	JHGR01
112	EXECUTION CASES : EXECUTION CASES	JHGR01
33	FINAL DECREE : FINAL DECREE	JHGR01
164	G.R. Cases : General Register No.	JHGR01
161	GUARDIANSHIP : GUARDIANSHIP	JHGR01
35	INSOLVENCY : INSOLVENCY	JHGR01
109	LAND ACQUISTAION APPL : LAND ACQUISTAION CASES	JHGR01
5	LAND REF. : LAND REF.	JHGR01
195	Letter Of Admn. : Letter Of Administration	JHGR01
158	MISC. CASE : MISCELLANEOUS CASE	JHGR01
105	MISC CASES : MISC CASES a) O-9 , R-4  b) O-	JHGR01
202	MISC CIV APP : MISC CIVIL APPLICATION	JHGR01
184	Misc Petition : Misc Petition	JHGR01
201	MIS CRI APP : MISC CRI. APPLICATION	JHGR01
106	Motor Accident Claims Cases : CLAIM CASES	JHGR01
15	NDPS. S. CASE : NDPS. S. CASE	JHGR01
198	Non F I R : Non F I R	JHGR01
143	Original Suit : Original Suit	JHGR01
175	ORIGINAL SUITS : Title Arbitration Suit	JHGR01
200	PARTITION APPEAL : PARTITION APPEAL	JHGR01
108	PROBATE CASE : PROBATE CASE	JHGR01
206	REVOCATION CASE : REVOCATION CASE	JHGR01
205	SC/ST Case : SC/ST Case	JHGR01
10	Sessions Trial : SESSION TRIAL	JHGR01
204	Special POCSO Case : Special POCSO Case	JHGR01
196	S.T. : Payment of Gratuty Act	JHGR01
111	Succession Certificate Case : SUCESSION	JHGR01
155	Title Eviction Suit : Title Eviction Suit	JHGR01
154	Title Mortgage Suit : Title Mortgage Suit	JHGR01
203	TRANSFER PETITION : TRANSFER PETITION	JHGR01
43	TRUST APPEAL : TRUST APPEAL	JHGR01
44	TRUST SUIT : TRUST SUIT	JHGR01
193	Vig Case : Vigilance P.S Case	JHGR01
170	Civil Misc. Case : Civil Misc. Case	JHGR05
161	Guardianship : Guardianship	JHGR05
157	Maintenance Alteration Case : Criminal(Misc.)CASE	JHGR05
105	MISC CASES a) O-9 , R-4  b) O-9, R-9 c) U/S 47CPC : MISC CASES a) O-9 , R-4  b) O-	JHGR05
200	Misc. Civil Application : Misc. Civil Application	JHGR05
199	Misc. Cri. Application : Misc. Cri. Application	JHGR05
156	Original Maintenance Case : Maintenance	JHGR05
107	Original Suit : MATRIMONIAL CASE	JHGR05
195	COMPLAINT CASE : COMPLAINT CASE	JHJR08
213	Criminal Misc Case : Criminal Misc Case	JHJR08
162	G.R.case : G.R.case	JHJR08
40	M.C.A : MISC CRIMINAL APPLICATION	JHJR08
10	Sessions Trial : SESSIONS TRIAL	JHJR08
186	C1 Eco.Offence Case : C1 Eco.Offence Case	JHJR03
185	C2 Eco.Offence Case : C2  Eco Offence Case	JHJR03
192	C.C.P : COMPANY COMPLAIN PETITION	JHJR03
169	Comp.Case (c1) : Complain Case (C1)	JHJR03
170	Comp. Case(c2) : Complain Case (C2)	JHJR03
171	Comp. Case (C3) : Complain Case (C3)	JHJR03
189	COMPLAINT CASE : COMPLAINT CASE	JHJR03
198	Complaint Case (Juvenile) : Complaint Case (Juvenile)	JHJR03
157	Criminal Misc. Case : Criminal Misc. Case	JHJR03
174	Cri.misc.petition : Cri.Misc.Petition	JHJR03
168	G.R. Case : G.R. Case	JHJR03
193	G.R. Case Split-A : GR Case Split-A	JHJR03
194	G.R. Case Split-B : GR Case Split-B	JHJR03
195	G.R. Case Split-C : GR Case Split-C	JHJR03
197	G.R.(Juvenile) : G.R.(Juvenile)	JHJR03
190	G.R.Suppl-A : G.R.Suppl-A	JHJR03
196	MISC. CRI. APPLICATION : MISC. CRI. APPLICATION	JHJR03
200	NDPS ACT(Juvenile) : NDPS ACT(Juvenile)	JHJR03
191	Police Act Cases : Police Act Cases	JHJR03
201	Railway Act( Juvenile) : Railway Act( Juvenile)	JHJR03
199	SPL.POCSO ACT (Juvenile) : SPL.POCSO ACT (Juvenile)	JHJR03
152	Antic.Bail Petition : Anticipatory Bail Petition	JHJR09
23	ARBITRATION CASE : ARBITRATION CASE	JHJR09
153	Bail Petition : Bail Petition	JHJR09
177	C.3 : C.3	JHJR09
197	C/3.s : C/3.s	JHJR09
178	C.7 : C.7	JHJR09
210	Children Case : Children Case	JHJR09
1	CIVIL APPEAL : CIVIL APPEAL	JHJR09
166	Civil Misc.Appeal : CIVIL MISC.APPEAL	JHJR09
212	CIVIL MISC CASE : CIVIL MISC CASE	JHJR09
195	Complaint Case : Complaint Case	JHJR09
163	Confiscation Appeal : Confiscation Appeal	JHJR09
12	Criminal Appeal : CRIMINAL APPEAL	JHJR09
164	Criminal Misc. : Criminal Misc.	JHJR09
13	Criminal Revision : Criminal Revision	JHJR09
205	E.C. Cases : E.C. Cases	JHJR09
184	Election Petition : ELECTION PETITION	JHJR09
211	Electricity Act Cases : Electricity Act Cases	JHJR09
168	Eviction Appeal : EVICTION APPEAL	JHJR09
112	Execution Case : EXECUTION CASE	JHJR09
162	G.R.case : G.R.case	JHJR09
190	G.R.s : G.R. suplimentary	JHJR09
193	G.R.s1 : G.R.s1	JHJR09
194	G.R.s2 : G.R.s2	JHJR09
161	GUARDIANSHIP CASE : GUARDIANSHIP CASE	JHJR09
109	LAND ACQUI. CASE : LAND ACQUI. CASE	JHJR09
146	Letter of Admin.Case : Letter of  Administration Case	JHJR09
173	Misc.Case : Misc.Case	JHJR09
41	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHJR09
40	MISC Cri. APPLICATION : MISC Cri. APPLICATION	JHJR09
187	Misc (p). Case : Misc.Petition Case	JHJR09
169	Mot.Acci.Claims Case : Motor Accident Claims Case	JHJR09
188	N.D.P.S. Case : N.D.P.S. Case	JHJR09
203	NDPS.s : NDPS Suppl	JHJR09
207	NDPSs1 : NDPSs1	JHJR09
143	Original Suit : ORIGINAL SUIT	JHJR09
108	PROBATE CASE : PROBATE CASE	JHJR09
179	R.A.case : R.A.case	JHJR09
201	Revocation Case : Revocation Case	JHJR09
192	SC-ST Case : SC-ST Case	JHJR09
202	SC-ST.s : SC-ST.s case	JHJR09
10	Sessions Trial : SESSIONS TRIAL	JHJR09
189	Spl POCSO Case : Spl POCSO Case	JHJR09
204	Spl POCSOs : Spl POCSOs	JHJR09
191	S.T.s : S.T. supplimentary	JHJR09
198	S.T.s1 : S.T.s1	JHJR09
209	S.T.s2 : S.T.s2	JHJR09
111	Succession Cert.Case : SUCCESSION CERTIFICATE CASES	JHJR09
167	Title. P.Appeal : Title. P. Appeal	JHJR09
172	Title. P. Suit : TITLE P. SUIT	JHJR09
208	Transfer Petition : Transfer Petition	JHJR09
183	Vigilance Case : Vigilance Case	JHJR09
206	Vigilance Case(S) : Vigilance Case(S)	JHJR09
158	CIVIL MISC.CASE : CIVIL. MISC CASE	JHJR04
172	Ev. Suit : Eviction Suit	JHJR04
112	EXECUITION CASES : EXECUITION CASES	JHJR04
33	FINAL DECREE : FINAL DECREE	JHJR04
105	MISC CASES a) O-9 , R-4  b) O-9, R-9 c) U/S 47CPC : MISC CASES a) O-9 , R-4  b) O-	JHJR04
186	MISC. CIVIL APPLICATION : MISC CIVIL APPLICATION	JHJR04
103	MONEY SUIT : MONEY SUIT	JHJR04
185	ORIGINAL SUIT : ORIGINAL SUIT	JHJR04
155	Title Eviction Suit : Title Eviction Suit	JHJR04
143	Title.Part.Evic Suit : Title.Partition.Eviction Suit	JHJR04
173	T.(p).s : Title partition Suit	JHJR04
152	Antic.Bail Petition : Anticipatory Bail Petition	JHJR07
23	ARBITRATION CASE : ARBITRATION CASE	JHJR07
153	Bail Petition : Bail Petition	JHJR07
175	C.1 : C.1	JHJR07
196	C/1.s : C/1.s	JHJR07
177	C.3 : C.3	JHJR07
197	C/3.s : C/3.s	JHJR07
178	C.7 : C.7	JHJR07
210	Children Case : Children Case	JHJR07
1	CIVIL APPEAL : CIVIL APPEAL	JHJR07
166	Civil Misc.Appeal : CIVIL MISC.APPEAL	JHJR07
212	CIVIL MISC CASE : CIVIL MISC CASE	JHJR07
195	Complaint Case : Complaint Case	JHJR07
163	Confiscation Appeal : Confiscation Appeal	JHJR07
12	Criminal Appeal : CRIMINAL APPEAL	JHJR07
164	Criminal Misc. : Criminal Misc.	JHJR07
13	Criminal Revision : Criminal Revision	JHJR07
205	E.C. Cases : E.C. Cases	JHJR07
184	Election Petition : ELECTION PETITION	JHJR07
211	Electricity Act Cases : Electricity Act Cases	JHJR07
168	Eviction Appeal : EVICTION APPEAL	JHJR07
112	Execution Case : EXECUTION CASE	JHJR07
162	G.R.case : G.R.case	JHJR07
190	G.R.s : G.R. suplimentary	JHJR07
193	G.R.s1 : G.R.s1	JHJR07
194	G.R.s2 : G.R.s2	JHJR07
161	GUARDIANSHIP CASE : GUARDIANSHIP CASE	JHJR07
109	LAND ACQUI. CASE : LAND ACQUI. CASE	JHJR07
146	Letter of Admin.Case : Letter of  Administration Case	JHJR07
173	Misc.Case : Misc.Case	JHJR07
41	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHJR07
40	MISC Cri. APPLICATION : MISC Cri. APPLICATION	JHJR07
187	Misc (p). Case : Misc.Petition Case	JHJR07
169	Mot.Acci.Claims Case : Motor Accident Claims Case	JHJR07
188	N.D.P.S. Case : N.D.P.S. Case	JHJR07
203	NDPS.s : NDPS Suppl	JHJR07
207	NDPSs1 : NDPSs1	JHJR07
143	Original Suit : ORIGINAL SUIT	JHJR07
108	PROBATE CASE : PROBATE CASE	JHJR07
179	R.A.case : R.A.case	JHJR07
201	Revocation Case : Revocation Case	JHJR07
192	SC-ST Case : SC-ST Case	JHJR07
202	SC-ST.s : SC-ST.s case	JHJR07
10	Sessions Trial : SESSIONS TRIAL	JHJR07
189	Spl POCSO Case : Spl POCSO Case	JHJR07
204	Spl POCSOs : Spl POCSOs	JHJR07
191	S.T.s : S.T. supplimentary	JHJR07
198	S.T.s1 : S.T.s1	JHJR07
209	S.T.s2 : S.T.s2	JHJR07
111	Succession Cert.Case : SUCCESSION CERTIFICATE CASES	JHJR07
167	Title. P.Appeal : Title. P. Appeal	JHJR07
172	Title. P. Suit : TITLE P. SUIT	JHJR07
208	Transfer Petition : Transfer Petition	JHJR07
183	Vigilance Case : Vigilance Case	JHJR07
206	Vigilance Case(S) : Vigilance Case(S)	JHJR07
47	ARBITRATION CASE : ARBITRATION CASE	JHJR02
165	CIVIL MISC.CASE : CIVIL MISC.CASE	JHJR02
191	Commercial Arbitration : Commercial Arbitration	JHJR02
190	Commercial Execution : Commercial Execution	JHJR02
189	Commercial Revocation : Commercial Revocation	JHJR02
188	COMMERCIAL SUIT : COMMERCIAL SUIT	JHJR02
90	DISSOLUTION OF PATNERSHIP : DISSOLUTION OF PATNERSHIP	JHJR02
104	EVICTION SUIT(U/S BBC ACT/ U/S 111 TP ACT) : EVICTION SUIT(U/S BBC ACT/ U/S	JHJR02
172	Ev. Suit : Eviction Suit	JHJR02
112	EXECUTION CASES : EXECUTION CASES	JHJR02
109	LAND ACQUISTAION CASES : LAND ACQUISTAION CASES	JHJR02
158	Misc. Case : Miscleneous Case	JHJR02
187	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHJR02
103	MONEY SUIT : MONEY SUIT	JHJR02
185	ORIGINAL SUIT : ORIGINAL SUIT	JHJR02
155	Title Eviction Suit : Title Eviction Suit	JHJR02
154	Title Mortgage Suit : Title Mortgage Suit	JHJR02
143	Title.Part.Evic Suit : Title.Partition.Eviction Suit	JHJR02
173	T.(p).s : Title partition Suit	JHJR02
152	Antic.Bail Petition : Anticipatory Bail Petition	JHJR06
153	Bail Petition : Bail Petition	JHJR06
175	C.1 : C.1	JHJR06
196	C/1.s : C/1.s	JHJR06
177	C.3 : C.3	JHJR06
197	C/3.s : C/3.s	JHJR06
178	C.7 : C.7	JHJR06
210	Children Case : Children Case	JHJR06
1	CIVIL APPEAL : CIVIL APPEAL	JHJR06
166	Civil Misc.Appeal : CIVIL MISC.APPEAL	JHJR06
176	Comp. Case (C2) : Comp. Case (C2)	JHJR06
195	Complaint Case : Complaint Case	JHJR06
163	Confiscation Appeal : Confiscation Appeal	JHJR06
12	Criminal Appeal : CRIMINAL APPEAL	JHJR06
164	Criminal Misc. : Criminal Misc.	JHJR06
13	Criminal Revision : Criminal Revision	JHJR06
184	Election Petition : ELECTION PETITION	JHJR06
211	Electricity Act Cases : Electricity Act Cases	JHJR06
168	Eviction Appeal : EVICTION APPEAL	JHJR06
112	Execution Case : EXECUTION CASE	JHJR06
212	G.R. Case : G.R. Case	JHJR06
190	G.R.s : G.R. suplimentary	JHJR06
193	G.R.s1 : G.R.s1	JHJR06
194	G.R.s2 : G.R.s2	JHJR06
161	GUARDIANSHIP CASE : GUARDIANSHIP CASE	JHJR06
109	LAND ACQUI. CASE : LAND ACQUI. CASE	JHJR06
146	Letter of Admin.Case : Letter of  Administration Case	JHJR06
173	Misc.Case : Misc.Case	JHJR06
41	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHJR06
40	MISC Cri. APPLICATION : MISC Cri. APPLICATION	JHJR06
187	Misc (p). Case : Misc.Petition Case	JHJR06
169	Mot.Acci.Claims Case : Motor Accident Claims Case	JHJR06
188	N.D.P.S. Case : N.D.P.S. Case	JHJR06
203	NDPS.s : NDPS Suppl	JHJR06
207	NDPSs1 : NDPSs1	JHJR06
143	Original Suit : ORIGINAL SUIT	JHJR06
108	PROBATE CASE : PROBATE CASE	JHJR06
179	R.A.case : R.A.case	JHJR06
201	Revocation Case : Revocation Case	JHJR06
192	SC-ST Case : SC-ST Case	JHJR06
202	SC-ST.s : SC-ST.s case	JHJR06
10	Sessions Trial : SESSIONS TRIAL	JHJR06
189	Spl POCSO Case : Spl POCSO Case	JHJR06
204	Spl POCSOs : Spl POCSOs	JHJR06
191	S.T.s : S.T. supplimentary	JHJR06
198	S.T.s1 : S.T.s1	JHJR06
209	S.T.s2 : S.T.s2	JHJR06
111	Succession Cert.Case : SUCCESSION CERTIFICATE CASES	JHJR06
213	TITLE APPEAL : TITLE APPEAL	JHJR06
183	Vigilance Case : Vigilance Case	JHJR06
206	Vigilance Case(S) : Vigilance Case(S)	JHJR06
182	Anti. Bail Petition : Anticipatory Bail Petition	JHJR01
23	ARBITRATION CASE : ARBITRATION CASE	JHJR01
181	Bail Petition : Bail Petition	JHJR01
209	Children Case : Children Case	JHJR01
188	CIVIL APPEAL : CIVIL APPEAL	JHJR01
2	CIVIL MISC. APPEAL : CIVIL MISC. APPEAL	JHJR01
212	Civil Misc. Case : Civil Misc. Case	JHJR01
165	Civil Misc. Pet : Civil Misc. petition	JHJR01
216	Commercial Appeal : Commercial Appeal	JHJR01
219	Commercial Arbitration : Commercial Arbitration	JHJR01
218	Commercial Execution : Commercial Execution	JHJR01
217	COMMERCIAL REVOCATION : COMMERCIAL REVOCATION CASE	JHJR01
215	Commercial Suit : Commercial Suit	JHJR01
169	Comp.Case (c1) : Complain Case (C1)	JHJR01
170	Comp. Case(c2) : Complain Case (C2)	JHJR01
193	Complaint Case : Complaint Case	JHJR01
164	Confiscation Appeal : confiscation Appeal	JHJR01
12	CRIMINAL APPEAL : CRIMINAL APPEAL	JHJR01
174	CRIMINAL MISC : CRIMINAL MISC CASE	JHJR01
13	CRIMINAL REVISION : CRIMINAL REVISION	JHJR01
157	Cri Misc.Case : Criminal Misc. Case	JHJR01
214	CYBER CRIME CASE : CYBER CRIME CASE	JHJR01
211	DrugsCosmetic : DrugsCosmetic	JHJR01
168	ELECTRICITY ACT CASE : Elect. Act Case	JHJR01
202	ELECTRICITY ACT CASE SPLIT A : ELECTRICITY ACT CASE SPLIT A	JHJR01
203	ELECTRICITY ACT CASE SPLIT B : ELECTRICITY ACT CASE SPLIT B	JHJR01
204	ELECTRICITY ACT CASE SPLIT C : ELECTRICITY ACT CASE SPLIT C	JHJR01
162	Ev.a : Eviction  Appeal	JHJR01
112	EXECUITION CASES : EXECUITION CASES	JHJR01
192	G.R. CASE : G.R. CASE	JHJR01
161	GUARDIANSHIP : GUARDIANSHIP	JHJR01
35	INSOLVENCY : INSOLVENCY	JHJR01
146	Letter Of Admin. Case : Letter of Administration Case	JHJR01
163	M.A.CLAIM CASE : MOTOR ACCIDENT CLAIMS CASE	JHJR01
200	M.A.CLAIM SUPPL-A : M.A.CLAIM SUPPL-A	JHJR01
158	Misc. Case : Miscleneous Case	JHJR01
208	MISC. CIVIL  APPLICATION : MISC CIVIL APPLICATION	JHJR01
207	MISC. CRI. APPLICATION : MISC. CRI. APPLICATION	JHJR01
185	MISC.PETITION A.I.R : MISC.PETITION A.I.R	JHJR01
140	MONEY APPEAL : MONEY APPEAL	JHJR01
15	N.D.P.S CASE : N.D.P.S CASE	JHJR01
199	N.D.P.S. Suppl Case : N.D.P.S. Suppl Case	JHJR01
30	ORIGINAL MAINTAINENCE CASE : ORIGINAL MAINTAINENCE CASE	JHJR01
143	ORIGINAL SUIT : ORIGINAL SUIT	JHJR01
108	PROBATE CASE : PROBATE CASE	JHJR01
177	REVOCATION : REVOCATION CASE	JHJR01
179	Rev. Succession : Revocation Succession	JHJR01
210	SC_ST Case : SC ST Case	JHJR01
10	SESSION TRIAL : SESSION TRIAL	JHJR01
196	Session Trial Split A : Session Trial Split A	JHJR01
197	Session Trial Split B : Session Trial Split B	JHJR01
201	Session Trial Split C : Session Trial Split C	JHJR01
198	Session Trial Suppl : Session Trial Suppl	JHJR01
213	SPL CASE : SPL CASE	JHJR01
11	SPL. CASE : SPL. CASE	JHJR01
16	SPL. POCSO CASE : SPL. POCSO CASE	JHJR01
194	SPL. POCSO SUPPL-A : SPL. POCSO SUPPL-A	JHJR01
183	SUCC. CERT. CASE : SUCCESSION CERTIFICATE CASE	JHJR01
189	Succsession Case : Succsession Case	JHJR01
1	TITLE APPEAL : TITLE APPEAL	JHJR01
190	TRANSFER PETITION : TRANSFER PETITION	JHJR01
153	Bail Petition : Bail Petition	JHJR10
175	C.1 : C.1	JHJR10
196	C/1.s : C/1.s	JHJR10
177	C.3 : C.3	JHJR10
197	C/3.s : C/3.s	JHJR10
178	C.7 : C.7	JHJR10
210	Children Case : Children Case	JHJR10
1	CIVIL APPEAL : CIVIL APPEAL	JHJR10
166	Civil Misc.Appeal : CIVIL MISC.APPEAL	JHJR10
195	Complaint Case : Complaint Case	JHJR10
163	Confiscation Appeal : Confiscation Appeal	JHJR10
12	Criminal Appeal : CRIMINAL APPEAL	JHJR10
164	Criminal Misc. : Criminal Misc.	JHJR10
13	Criminal Revision : Criminal Revision	JHJR10
205	E.C. Cases : E.C. Cases	JHJR10
184	Election Petition : ELECTION PETITION	JHJR10
211	Electricity Act Cases : Electricity Act Cases	JHJR10
168	Eviction Appeal : EVICTION APPEAL	JHJR10
112	Execution Case : EXECUTION CASE	JHJR10
162	G.R.case : G.R.case	JHJR10
190	G.R.s : G.R. suplimentary	JHJR10
193	G.R.s1 : G.R.s1	JHJR10
194	G.R.s2 : G.R.s2	JHJR10
161	GUARDIANSHIP CASE : GUARDIANSHIP CASE	JHJR10
109	LAND ACQUI. CASE : LAND ACQUI. CASE	JHJR10
146	Letter of Admin.Case : Letter of  Administration Case	JHJR10
213	MAINT. ALT. CASE : MAINTENACE ALTERATION CASE	JHJR10
173	Misc.Case : Misc.Case	JHJR10
41	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHJR10
40	MISC Cri. APPLICATION : MISC Cri. APPLICATION	JHJR10
187	Misc (p). Case : Misc.Petition Case	JHJR10
169	Mot.Acci.Claims Case : Motor Accident Claims Case	JHJR10
188	N.D.P.S. Case : N.D.P.S. Case	JHJR10
203	NDPS.s : NDPS Suppl	JHJR10
207	NDPSs1 : NDPSs1	JHJR10
212	ORG MAINTENANCE CASE : ORG MAINTENANCE CASE	JHJR10
143	Original Suit : ORIGINAL SUIT	JHJR10
108	PROBATE CASE : PROBATE CASE	JHJR10
179	R.A.case : R.A.case	JHJR10
201	Revocation Case : Revocation Case	JHJR10
192	SC-ST Case : SC-ST Case	JHJR10
202	SC-ST.s : SC-ST.s case	JHJR10
10	Sessions Trial : SESSIONS TRIAL	JHJR10
189	Spl POCSO Case : Spl POCSO Case	JHJR10
204	Spl POCSOs : Spl POCSOs	JHJR10
191	S.T.s : S.T. supplimentary	JHJR10
198	S.T.s1 : S.T.s1	JHJR10
209	S.T.s2 : S.T.s2	JHJR10
111	Succession Cert.Case : SUCCESSION CERTIFICATE CASES	JHJR10
167	Title. P.Appeal : Title. P. Appeal	JHJR10
172	Title. P. Suit : TITLE P. SUIT	JHJR10
208	Transfer Petition : Transfer Petition	JHJR10
183	Vigilance Case : Vigilance Case	JHJR10
206	Vigilance Case(S) : Vigilance Case(S)	JHJR10
165	CIVIL MISC CASE : CIVIL MISC CASE	JHJR05
112	EXECUITION CASES : EXECUITION CASES	JHJR05
161	GUARDIANSHIP : GUARDIANSHIP	JHJR05
156	Maintainance : Maintainance	JHJR05
188	MAINT. ALT. CASE : MAINTENACE ALTERATION CASE	JHJR05
107	MATRIMONIAL CASE : MATRIMONIAL CASE	JHJR05
158	Misc. Case : Miscleneous Case	JHJR05
192	Misc. Civil Application : Misc. Civil Application	JHJR05
191	MISC. CRI. APPLICATION : MISC. CRI. APPLICATION	JHJR05
30	ORG MAINTENANCE CASE : ORIGINAL MAINTENANCE CASE	JHJR05
187	ORIGINAL SUIT : ORIGINAL SUIT	JHJR05
186	Title Maint. Suit : Title Maintenance suit	JHJR05
166	Title Mat. Suit : Title Matrimonial Suit	JHJR05
47	ARBITRATION CASE : ARBITRATION CASE	JHJM02
93	CAVEAT : CAVEAT	JHJM02
105	CIVIL MISC. CASE : CIVIL MISC. CASE	JHJM02
181	COMMERCIAL SUIT : COMMERCIAL SUIT	JHJM02
104	EVICTION SUIT(U/S BBC ACT/ U/S 111 TP ACT) : EVICTION SUIT(U/S BBC ACT/ U/S	JHJM02
112	EXECUTION CASE : EXECUTION CASE	JHJM02
33	FINAL DECREE : FINAL DECREE	JHJM02
109	LAND ACQUISITION CASE : LAND ACQUISITION CASE	JHJM02
180	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHJM02
167	MONEY EXECUTION : MONEY EXECUTION	JHJM02
103	MONEY SUIT : MONEY SUIT	JHJM02
143	ORIGINAL SUIT : ORIGINAL SUIT	JHJM02
155	TITLE EVICTION SUIT : TITLE EVICTION SUIT	JHJM02
102	TITLE P. SUIT : TITLE P. SUIT	JHJM02
152	ANTICIPATORY BAIL PETITION : ANTICIPATORY BAIL PETITION	JHJM01
164	BAIL CANCELLATION PETITION : BAIL CANCELLATION PETITION	JHJM01
166	BAIL PETITION : BAIL PETITION	JHJM01
93	CAVEAT : CAVEAT	JHJM01
188	CHILDREN CASE : CHILDREN CASE	JHJM01
1	CIVIL APPEAL : CIVIL APPEAL	JHJM01
2	CIVIL MISC. APPEAL : CIVIL MISC. APPEAL	JHJM01
105	CIVIL MISC. CASE : CIVIL MISC. CASE	JHJM01
189	COMMERCIAL APPEAL : COMMERCIAL APPEAL	JHJM01
142	COMPLAINT CASE : COMPLAINT CASE	JHJM01
159	CR. CASE COMPLAINT (O) : CR. CASE COMPLAINT (O)	JHJM01
12	CRIMINAL APPEAL : CRIMINAL APPEAL	JHJM01
13	CRIMINAL REVISION : CRIMINAL REVISION	JHJM01
157	CRL. MISC.CASE : CRL. MISC.CASE	JHJM01
173	CRL. MISC. EXECUTION : CRL. MISC. EXECUTION	JHJM01
185	CYBER CASE : CYBER CASE	JHJM01
147	DOMESTIC VIOLENCE ACT 2005 : DOMESTIC VIOLENCE ACT 2005	JHJM01
17	DRUGS AND COSMETICS ACT : DRUGS AND COSMETICS ACT	JHJM01
8	ELECTRICITY ACT CASES : ELECTRICITY ACT CASES	JHJM01
104	EVICTION SUIT(U/S BBC ACT/ U/S 111 TP ACT) : EVICTION SUIT(U/S BBC ACT/ U/S	JHJM01
112	EXECUTION CASES : EXECUTION CASES	JHJM01
33	FINAL DECREE : FINAL DECREE	JHJM01
162	G.R. CASES : G.R. CASES	JHJM01
109	LAND ACQUISITION CASE : LAND ACQUISITION CASE	JHJM01
182	LETTER OF ADMINISTRATION : LETTER OF ADMINISTRATION	JHJM01
181	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHJM01
180	MISC. CRIMINAL APPLICATION : MISC. CRIMINAL APPLICATION	JHJM01
167	MONEY EXECUTION : MONEY EXECUTION	JHJM01
103	MONEY SUIT : MONEY SUIT	JHJM01
106	MOTOR ACCIDENT CLAIMS CASES : MOTOR ACCIDENT CLAIMS CASES	JHJM01
15	N.D.P.S CASE : N.D.P.S CASE	JHJM01
107	ORIGINAL SUIT : ORIGINAL SUIT	JHJM01
108	PROBATE CASE : PROBATE CASE	JHJM01
137	REVOCATION CASE : REVOCATION CASE	JHJM01
163	SC AND ST CASE : SC AND ST CASE	JHJM01
10	SESSIONS TRIAL : SESSIONS TRIAL	JHJM01
190	SPECIAL NIA : SPECIAL NIA	JHJM01
179	SPECIAL POCSO CASE : SPECIAL POCSO CASE	JHJM01
14	SPLIT  COMPLAINT CASE   A : SPLIT  COMPLAINT CASE   A	JHJM01
16	SPLIT COMPLAINT CASE B : SPLIT COMPLAINT CASE B	JHJM01
186	SPLIT CYBER CASE A : SPLIT CYBER CASE A	JHJM01
187	SPLIT CYBER CASE B : SPLIT CYBER CASE B	JHJM01
183	SPLIT ELECTRICITY ACT CASES A : SPLIT ELECTRICITY ACT CASES A	JHJM01
184	SPLIT ELECTRICITY ACT CASES B : SPLIT ELECTRICITY ACT CASES B	JHJM01
41	SPLIT GR  A : SPLIT GR  A	JHJM01
42	SPLIT GR  B : SPLIT GR  B	JHJM01
43	SPLIT GR  C : SPLIT GR  C	JHJM01
44	SPLIT GR  D : SPLIT GR  D	JHJM01
26	SPLIT N.D.P.S CASES A : SPLIT N.D.P.S CASES A	JHJM01
27	SPLIT N.D.P.S CASES B : SPLIT N.D.P.S CASES B	JHJM01
25	SPLIT N.D.P.S CASES C : SPLIT N.D.P.S CASES C	JHJM01
177	SPLIT OCR A : SPLIT OCR A	JHJM01
178	SPLIT OCR B : SPLIT OCR B	JHJM01
151	SPLIT SC AND ST CASE A : SPLIT SC AND ST CASE A	JHJM01
80	SPLIT  SESSIONS   A : SPLIT  SESSIONS   A	JHJM01
81	SPLIT  SESSIONS   B : SPLIT  SESSIONS   B	JHJM01
76	SPLIT SESSIONS   C : SPLIT SESSIONS   C	JHJM01
46	SPLIT SESSIONS D : SPLIT SESSIONS D	JHJM01
191	SPLIT SESSIONS E : SPLIT SESSIONS E	JHJM01
31	SPLIT SPECIAL POCSO CASE A : SPLIT SPECIAL POCSO CASE A	JHJM01
32	SPLIT SPECIAL POCSO CASE B : SPLIT SPECIAL POCSO CASE B	JHJM01
192	SPLIT SPECIAL POCSO CASE C : SPLIT SPECIAL POCSO CASE C	JHJM01
6	SPLIT TITLE APPEAL A : SPLIT TITLE APPEAL A	JHJM01
111	SUCCESSION CERTIFICATE CASE : SUCCESSION CERTIFICATE CASE	JHJM01
155	TITLE EVICTION SUIT : TITLE EVICTION SUIT	JHJM01
102	TITLE P. SUIT : TITLE P. SUIT	JHJM01
170	TRANSFER PETITION (CIVIL) : TRANSFER PETITION (CIVIL)	JHJM01
169	TRANSFER PETITION (CR) : TRANSFER PETITION (CR)	JHJM01
148	WEIGHT AND MEASUREMENT ACT : WEIGHT AND MEASUREMENT ACT	JHJM01
105	CIVIL MISC. CASE : CIVIL MISC. CASE	JHJM05
173	CRL. MISC. EXECUTION : CRL. MISC. EXECUTION	JHJM05
161	GUARDIANSHIP CASE : GUARDIANSHIP CASE	JHJM05
174	MAINTENANCE ALTERATION CASE : MAINTENANCE ALTERATION CASE	JHJM05
171	ORIGINAL MAINTENANCE CASE : ORIGINAL MAINTENANCE CASE	JHJM05
107	ORIGINAL SUIT : ORIGINAL SUIT	JHJM05
142	COMPLAINT CASE : COMPLAINT CASE	JHJM03
159	CR. CASE COMPLAINT (O) : CR. CASE COMPLAINT (O)	JHJM03
157	CRL. MISC.CASE : CRL. MISC.CASE	JHJM03
173	CRL. MISC.EXECUTION : CRL. MISC.EXECUTION	JHJM03
147	DOMESTIC VIOLENCE ACT 2005 : DOMESTIC VIOLENCE ACT 2005	JHJM03
162	G.R. CASES : G.R. CASES	JHJM03
180	MISC. CRIMINAL APPLICATION : MISC. CRIMINAL APPLICATION	JHJM03
14	SPLIT COMPLAINT A : SPLIT COMPLAINT CASE A	JHJM03
16	SPLIT COMPLAINT B : SPLIT COMPLAINT CASE B	JHJM03
41	SPLIT GR A : SPLIT GR A	JHJM03
42	SPLIT GR B : SPLIT GR B	JHJM03
43	SPLIT GR C : SPLIT GR C	JHJM03
44	SPLIT GR D : SPLIT GR D	JHJM03
181	SPLIT GR E : SPLIT GR E	JHJM03
177	SPLIT OCR A : SPLIT OCR A	JHJM03
178	SPLIT OCR B : SPLIT OCR B	JHJM03
148	WEIGHT AND MEASUREMENT ACT : WEIGHT AND MEASUREMENT ACT	JHJM03
156	Ori. Maint. Case : Original Maintenance Case	JHCH05
152	Anticipatory Bail Petition : Anticipatory Bail Petition	JHCH01
153	Bail Petition : Bail Petition	JHCH01
171	Child Act Special Case : Child Act Special Case	JHCH01
1	Civil Appeal : Civil Appeal	JHCH01
172	Civil Revision : Civil Revision	JHCH01
206	Commercial Arbitration case : Commercial Arbitration case	JHCH01
205	Commercial Suit : Commercial Suit	JHCH01
159	Complaint Case : Complaint Case	JHCH01
201	Confiscation Appeal : Confiscation Appeal	JHCH01
12	Criminal Appeal : Criminal Appeal	JHCH01
157	Criminal Misc. : Criminal Misc.	JHCH01
13	Criminal Revision : Criminal Revision	JHCH01
202	Drugs and Cosmetic Special Case : Drugs and Cosmetic Special Case	JHCH01
177	Electricity Act Case : Electrycity Act Case	JHCH01
112	Execution Cases : EXECUTION CASES	JHCH01
199	FERA / FEMA Case : FERA/FEMA Case	JHCH01
21	G.R. Case : G.R. Case	JHCH01
161	Guardianship Case : Guardianship Case	JHCH01
200	Human Rights Case : Human Rights Case	JHCH01
178	Insolvency : Insolvency	JHCH01
197	Juvenile Case : Juvenile Case	JHCH01
175	Land Acq. Appeal : Land Acq. Appeal	JHCH01
189	Letter of Administration : Letter of Administration	JHCH01
204	Misc. Case : Misc. Case	JHCH01
105	Misc. Case M.V. : Misc. Case M.V.	JHCH01
2	Misc. Civil Appeal : Misc. Civil Appeal	JHCH01
174	Misc. Civil Application : Misc. Civil Application	JHCH01
173	Misc. Cri. Application : Misc. Cri. Application	JHCH01
106	Motor Accident Claim : Motor Accident Claims Cases	JHCH01
26	N.D.P.S. Case : N.D.P.S. Case	JHCH01
143	Original Suit : Original Suit	JHCH01
166	POCSO Act Special Case : POCSO Act Special Case	JHCH01
184	P.O.T.A. Case : POTA Case	JHCH01
193	Regular C.B.I. Case : Regular CBI Case	JHCH01
169	Revocation Case : Revocation Case	JHCH01
29	SC ST Special Case : SC ST Special Case	JHCH01
10	Session Trial : Session Trial	JHCH01
111	Succession Certificate Case : SUCESSION CERTIFICATE CASE	JHCH01
168	Transfer Petition : Tansfer Petition	JHCH01
188	Vigilance Case : Vigilance Case	JHCH01
157	Criminal Mislaneous : Criminal Mislaneous	JHPK03
165	Crl. Misc Case : Criminal Misc Case	JHPK03
175	EC Cases : EC Cases	JHPK03
149	Excise Act : Excise Act	JHPK03
162	G.R. Cases : General Register	JHPK03
169	G.R.Cases Suppl.A : GR Suppl.A	JHPK03
171	G.R.Cases Suppl.B : GR Cases Suppl.B	JHPK03
176	Juvenile Cases : Juvenile Cases	JHPK03
172	Misc. Criminal Application : Misc. Criminal Application	JHPK03
158	Mislaneous : Mislaneous	JHPK03
174	M.W.Claim Case : M.W.Claim Case	JHPK03
173	Special POCSO Case : Special POCSO Case	JHPK03
89	ADMINISTRATIVE SUIT : ADMINISTRATIVE SUIT	JHPK02
23	ARBITRATION CASE : ARBITRATION CASE	JHPK02
105	CIVIL MISC CASES : MISC CASES a) O-9 , R-4  b) O-	JHPK02
172	Commercial (Arbitration) Case : Commercial (Arbitration) Case	JHPK02
174	Commercial Suit : Commercial Suit	JHPK02
98	CROSS SUIT : CROSS SUIT	JHPK02
100	DECLARATIVCE SUIT : DECLARATIVCE SUIT	JHPK02
8	ELECT. PETN : ELECT. PETN	JHPK02
104	EVICTION SUIT : EVICTION SUIT	JHPK02
112	EXECUITION CASES : TITLE EXECUITION CASES	JHPK02
33	FINAL DECREE : FINAL DECREE	JHPK02
135	INJUNCTION O-39 , R1, 2 : INJUNCTION O-39 , R1, 2	JHPK02
97	INTERPLEADER SUIT : INTERPLEADER SUIT	JHPK02
146	L.A : L.A	JHPK02
171	Land Acquisition Execution Case : Land Acquisition Execution Case	JHPK02
170	Land Acquisition Misc Case : Land Acqusition Misc Case	JHPK02
109	LAND ACQUISTAION CASES : LAND ACQUISITION CASES	JHPK02
110	LUNACY OR ADOPTION OR INSOLVANCY : LUNACY  ADOPTION OR INSOLVANCY	JHPK02
169	Misc. Civil Application : Misc. Civil Application	JHPK02
158	Mislaneous : Mislaneous	JHPK02
173	Money Execution : Money Execution	JHPK02
103	MONEY SUIT : MONEY SUIT	JHPK02
143	Original Suit : Original Suit	JHPK02
102	PARTION SUIT OR TITLE PARTION SUIT : PARTION SUIT TITLE PARTION S	JHPK02
17	REG. CIVIL SUIT : REG. CIVIL SUIT	JHPK02
120	RESTORATION OF SUITS 0-9 R-4 , 0-9 , R -9 : RESTORATION OF SUITS 0-9 R-4 ,	JHPK02
19	SPL.CIV. SUIT : SPL.CIV. SUIT	JHPK02
93	SUIT BY MINOR : SUIT BY MINOR	JHPK02
95	SUMMARY SUIT : SUMMARY SUIT	JHPK02
168	Title Account Suit : Title Account Suit	JHPK02
167	Title (damage) Suit : Title (Damage) Suit	JHPK02
155	Title Eviction Suit : Title Eviction Suit	JHPK02
166	Title Pre emption Suit : Title Pre Emption Suit	JHPK02
148	Title Suit. : Title Suit.	JHPK02
171	Anticipatory Bail Petition : Anticipatory Bail Petition	JHPK01
23	ARBITRATION CASE : ARBITRATION CASE	JHPK01
170	Bail Petition : Bail Petition	JHPK01
184	Children Case : Children Case	JHPK01
1	Civil Appeal : Civil Appeal	JHPK01
2	CIVIL MISC APPEAL : CIVIL MISC APPEAL	JHPK01
105	CIVIL MISC CASES : MISC CASES a) O-9 , R-4  b) O-	JHPK01
185	Commercial Suit : Commercial Suit	JHPK01
142	Complaint Case : Complaint Case	JHPK01
12	Criminal Appeal : CRI. APPEAL	JHPK01
165	Criminal Misc. : Criminal Misc.	JHPK01
157	Criminal Mislaneous : Criminal Mislaneous	JHPK01
13	Criminal Revision : CRI. REV APP.	JHPK01
169	Crl. Misc Petition : Criminal Misc Petition	JHPK01
172	Crl. Misc. Tr. Pet. : Crl. Misc. Tr. Petition	JHPK01
188	Drug and Cosmetics Act Cases : Drug and Cosmetics Act Cases	JHPK01
183	Electricity Act Case : Electricity Act Case	JHPK01
162	GR Case : General Register	JHPK01
186	Guardianship Case : Guardianship Case	JHPK01
109	LAND ACQUISTAION CASES : LAND ACQUISTAION CASES	JHPK01
5	LAND REF. : LAND REF.	JHPK01
146	Letter of Admin. : Letter of Admin.	JHPK01
187	Maintenance : Maintenance	JHPK01
180	Misc. Civil Application : Misc. Civil Application	JHPK01
179	Misc. Criminal Application : Misc. Criminal Application	JHPK01
158	Mislaneous : Mislaneous	JHPK01
140	MONEY APPEAL : MONEY APPEAL	JHPK01
35	Motor Accident Claims Cases : Motor Accident Claims Cases	JHPK01
15	N.D.P.S Case : N.D.P.S Case	JHPK01
174	NDPS Suppl. : NDPS Suppl.	JHPK01
182	Original Suit : Original Suit	JHPK01
108	PROBATE CASE : PROBATE CASE	JHPK01
178	Revocation Case : Revocation Case	JHPK01
25	SC and ST Case : SC and ST Cases	JHPK01
40	SC. And ST. Case : SC and ST Case	JHPK01
173	Sessions Case Suppl. : Sessions Case Suppl.	JHPK01
10	Sessions Trial : SESSION CASE	JHPK01
181	Special POCSO Case : Special POCSO Case	JHPK01
111	Sucessions Certificate Case : SUCESSION CERTIFICATE CASES	JHPK01
177	Title Eviction Appeal : Title Eviction Appeal	JHPK01
154	Title Misc Appeal : Title Misc Appeal	JHPK01
176	Transfer Petition : Transfer Petition	JHPK01
105	CIVIL MISC CASES : CIVIL MISC CASES	JHPK05
157	Criminal Mislaneous : Criminal Mislaneous	JHPK05
112	EXECUITION CASES : EXECUITION CASES	JHPK05
161	GUARDIANSHIP : GUARDIANSHIP	JHPK05
98	Maintenance Alteration Case : Maintenance Alteration Case	JHPK05
107	MATRIMONIAL CASE : MATRIMONIAL CASE	JHPK05
169	Misc. Criminal Application : Misc. Criminal Application	JHPK05
158	Mislaneous : Mislaneous	JHPK05
20	Mislaneous Crl. Rel. : Mislaneous Crl.Rel.	JHPK05
165	Original Maintenance : Original Maintenance	JHPK05
163	Original Suit : Original Suit	JHPK05
164	Title (divorce) Suit : Title (Divorce) Suit	JHPK05
190	BAIL PETITION : BAIL PETITION	JHSK03
199	B.O.C ACT : B.O.C ACT	JHSK03
176	C 1 : C 1	JHSK03
177	C 2 : C 2	JHSK03
178	C 3 : C 3	JHSK03
194	COAL MINES ACT : COAL MINES ACT	JHSK03
212	C.O.(ECO. Off.) : C.O.(ECO. Off.)	JHSK03
142	Complaint Case : Complaint Case	JHSK03
175	Complaint Case N : Complaint Case N	JHSK03
195	Contract Labour Act : Contract Labour Act	JHSK03
159	Cr. Case Complaint (O) : Cr. Case Complaint (O)	JHSK03
191	CR. EXECUTION CASE : CR. EXECUTION CASE	JHSK03
189	CRI. BAIL APPLN. : CRI. BAIL APPLN.	JHSK03
21	CRI. CASE : CRI. CASE	JHSK03
184	Cri. Misc. Case(N) : Cri. Misc. Case(N)	JHSK03
80	DISTRESS WARRENT : DISTRESS WARRENT	JHSK03
147	Domestic Violence Act 2005 : Domestic Violence Act 2005	JHSK03
214	EC Cases : EC Cases	JHSK03
202	Employees Provident Fund : Employees Provident Fund	JHSK03
206	Employement Exchange : Employement Exchange	JHSK03
201	Equal Remuneration Act Case : Equal Remuneration Act Case	JHSK03
32	E.S.I. ACT  CASE : E.S.I. ACT  CASE	JHSK03
149	Excise Act : Excise Act	JHSK03
193	FACTORY ACT : FACTORY ACT	JHSK03
211	Food Safety and standard Act : Food Safety and standard Act	JHSK03
172	G. O.(f) : G. O.(F)	JHSK03
162	G. R. Cases(N) : G. R. Cases(N)	JHSK03
192	GR pre Cog. : GR pre Cog.	JHSK03
188	GR(s) : GR(s)	JHSK03
205	Industrial Disputes Act : Industrial Disputes Act	JHSK03
210	Information Request Case : Information Request Case	JHSK03
209	Jharkhand Shop and Estab. Act : Jharkhand Shop and Estab. Act	JHSK03
38	MESNE PROFIT : MESNE PROFIT	JHSK03
204	Minimum Wages Act : Minimum Wages Act	JHSK03
215	MISCCRAPPL(N) : MISC CRIMINAL APPLICATION(N)	JHSK03
173	Misc Petition : Misc Petition	JHSK03
216	N.D.P.S Case : N.D.P.S Case	JHSK03
208	Non F I R : Non F I R	JHSK03
30	OTHER MISC. CRI. APPLN. : OTHER MISC. CRI. APPLN.	JHSK03
200	Payment of Wage Act : Payment of Wage Act	JHSK03
203	PREVENTATION OF FOOD ADULTRATI : PREVENTATION OF FOOD ADULTRATI	JHSK03
196	Prevention of Cruelty Against : Prevention of Cruelty Against	JHSK03
197	Rail Case : Rail Case	JHSK03
213	RC Case : RC Case	JHSK03
20	REG. CRI. CASE : REG. CRI. CASE	JHSK03
24	Regular Bail  ( B P ) : Regular Bail (B.P.)	JHSK03
31	REVIEW APPLICATION : REVIEW APPLICATION	JHSK03
25	SPL. CRI. MA : SPL. CRI. MA	JHSK03
198	Under Railway Act : Under Railway Act	JHSK03
207	Vigilance P.S.Case : Vigilance P.S.Case	JHSK03
148	Weight and Measurement Act : Weight and Measurement Act	JHSK03
160	Weight   measurement Act 1985 : Weight  measurement Act 1985	JHSK03
89	ADMINISTRATIVE SUITE : ADMINISTRATIVE SUITE	JHSK04
138	APP. OF RES. SUBJUDICE U/S 10 : APP. OF RES. SUBJUDICE U/S 10	JHSK04
23	ARBITRATION CASE : ARBITRATION CASE	JHSK04
47	ARBITRATION R.D. : ARBITRATION R.D.	JHSK04
171	CIVIL MISC. CASE : CIVIL MISC. CASE	JHSK04
48	CONTEMPT PROCEEDINGS : CONTEMPT PROCEEDINGS	JHSK04
98	CROSS SUIT : CROSS SUIT	JHSK04
100	Declarativce  Suit : DECLARATORY SUIT	JHSK04
90	DISSOLUTION OF PATNERSHIP : DISSOLUTION OF PATNERSHIP	JHSK04
8	ELECT. PETN : ELECT. PETN	JHSK04
170	Eviction Suit : EVICTION SUIT	JHSK04
112	EXECUITION CASES(N) : EXECUITION CASES(N)	JHSK04
33	FINAL DECREE : FINAL DECREE	JHSK04
37	GUARDIAN and WARDS CASE : GUARDIAN and WARDS CASE	JHSK04
135	INJUNCTION O-39 , R1, 2 : INJUNCTION O-39 , R1, 2	JHSK04
35	INSOLVENCY : INSOLVENCY	JHSK04
97	INTERPLEADER SUIT : INTERPLEADER SUIT	JHSK04
109	LAND ACQUISTAION CASES : LAND ACQUISTAION CASES	JHSK04
5	LAND REF. : LAND REF.	JHSK04
189	L.A.Ref : L.A.Ref	JHSK04
110	LUNACY/ ADOPTION/ INSOLVANCY : LUNACY/ ADOPTION/ INSOLVANCY	JHSK04
38	MESNE PROFIT : MESNE PROFIT	JHSK04
105	Misc Cases a)O-9 : Misc Cases a)O-9	JHSK04
191	Misc Civil Appl.(N) : MISC. CIVIL APPLICATION(N)	JHSK04
187	Misc. Petition : Misc. Petition	JHSK04
158	Mislaneous : Mislaneous	JHSK04
103	MONEY SUIT : MONEY SUIT	JHSK04
143	Original Suit(N) : Original Suit(N)	JHSK04
45	PAUPER APPLICATION : PAUPER APPLICATION	JHSK04
17	REG. CIVIL SUIT : REG. CIVIL SUIT	JHSK04
28	REGULAR PETITION : REGULAR PETITION	JHSK04
42	RENT SUIT : RENT SUIT	JHSK04
120	RESTORATION OF SUITS 0-9 R-4 , 0-9 , R -9 : RESTORATION OF SUITS 0-9 R-4 ,	JHSK04
121	SETTING  ASIDE EXPARTE ORDER 0-9 - R -13 : SETTING  ASIDE EXPARTE ORDER 0	JHSK04
41	SMALL CAUSE SUIT : SMALL CAUSE SUIT	JHSK04
19	SPL.CIV. SUIT : SPL.CIV. SUIT	JHSK04
29	SPL. DARKHAST : SPL. DARKHAST	JHSK04
94	SUIT BNY PERSON OF UNSOIUND MIND : SUIT BNY PERSON OF UNSOIUND MI	JHSK04
92	SUIT BY  CORPORATION/FIRM/TRUSTEE/INDEGENT PERSON : SUIT BY  CORPORATION/FIRM/TRUS	JHSK04
93	SUIT BY MINOR : SUIT BY MINOR	JHSK04
91	SUITE BY GOVT OR PUBLIC  OFFICERS IN OFFICIAL CAPA : SUITE BY GOVT OR PUBLIC  OFFIC	JHSK04
101	SUIT FOR PERPERTUAL INJECTION : SUIT FOR PERPERTUAL INJECTION	JHSK04
99	SUIT FOR SPECIAL PERFORMANCE OF CONTRACT : SUIT FOR SPECIAL PERFORMANCE O	JHSK04
96	SUIT OF PUBLIC NAUSIANCE : SUIT OF PUBLIC NAUSIANCE	JHSK04
22	SUMMARY CASE : SUMMARY CASE	JHSK04
95	SUMMARY SUIT : SUMMARY SUIT	JHSK04
154	Title Mortgage Suit : Title Mortgage Suit	JHSK04
169	TITLE(PARTITION)SUIT : TITLE(PARTITION)SUIT	JHSK04
188	Title Suit : Title Suit	JHSK04
190	Title (Trade Mark) Suit : Title (Trade Mark) Suit	JHSK04
152	Anticipatory Bail Petition(N) : Anticipatory Bail Petition(N)	JHSK01
215	ARBITRATION APPEAL : ARBITRATION APPEAL	JHSK01
24	Bail Petition(N) : Bail Petition(N)	JHSK01
194	Children Case : Children Case	JHSK01
1	Civil Appeal(N) : Civil Appeal(N)	JHSK01
171	Civil Misc. Appeal(N) : Civil Misc. Appeal(N)	JHSK01
200	Civil Misc .Case : Civil Misc .Case	JHSK01
214	Civil Revision : Civil Revision	JHSK01
106	CLAIM CASES : CLAIM CASES	JHSK01
208	Coal Bearing Case : Coal Bearing Case	JHSK01
195	Commercial Case : Commercial Case	JHSK01
210	Commercial Revocation : Commercial Revocation	JHSK01
209	Comm. Exec : Comm. Exec	JHSK01
142	COMPLAINT CASE : COMPLAINT CASE	JHSK01
12	Criminal Appeal(N) : Criminal Appeal(N)	JHSK01
165	Criminal (Misc) Case : Criminal (Misc) Case	JHSK01
173	Criminal Misc Case (N) : Criminal Misc.case(N)	JHSK01
13	Criminal Revision(N) : Criminal Revision(N)	JHSK01
193	CYBER CASE : CYBER CASE	JHSK01
211	Drug Cosmrtic : Drug Cosmrtic	JHSK01
8	ELECTRICITY ACT CASE : ELECTRICITY ACT CASE	JHSK01
182	Eviction Appeal : EVICTION APPEAL	JHSK01
112	EXECUITION CASES : EXECUITION CASES	JHSK01
33	FINAL DECREE : FINAL DECREE	JHSK01
162	G. R. : G. R.	JHSK01
161	Guardianship Case(N) : Guardianship Case(N)	JHSK01
35	INSOLVENCY : INSOLVENCY	JHSK01
5	LAND Acq. Application : LAND Acq. Application	JHSK01
109	LAND ACQUISTAION CASES : LAND ACQUISTAION CASES	JHSK01
146	L.aRef : L.ARef	JHSK01
185	Letter Of Administration : LETTERS OF ADMINISTRATION	JHSK01
213	MISC CIVIL APPL(N) : MICIVAPPL(N)	JHSK01
212	MISC Cri. Appli(N) : MCRAPPL(N)	JHSK01
205	Miscelaneous Case MACT : Miscelaneous Case MACT	JHSK01
187	Misc. Petition : Civil Misc. Petition	JHSK01
197	Misc RC : Misc RC	JHSK01
140	MONEY APPEAL : MONEY APPEAL	JHSK01
168	Motor Accident Claims Cases(N) : Motor Accident Claims Cases(N)	JHSK01
15	N.D.P.S Case (N) : N.D.P.S Case(N)	JHSK01
203	Non F I R : Non F I R	JHSK01
196	Original Suit : Original Suit	JHSK01
102	PARTION SUIT / TITLE PARTION SUIT : PARTION SUIT / TITLE PARTION S	JHSK01
189	POCSO : POCSO CASE	JHSK01
188	POSCO : POCSCO CASE	JHSK01
198	POTA Case : POTA Case	JHSK01
108	PROBATE CASE : PROBATE CASE	JHSK01
206	Regular CBI Case : Regular CBI Case	JHSK01
120	Revocation Case(N) : Revocation Case(N)	JHSK01
183	S C/ S T- Case(N) : S C/ S T- CASE(N)	JHSK01
10	Sessions Trial(N) : SESSIONS TRIAL(N)	JHSK01
199	Spl. NIA : Spl. NIA	JHSK01
111	SUCESSION CERTIFICATE CASES(N) : SUCESSION CERTIFICATE CASES(N)	JHSK01
81	SUM. CIVIL SUIT : SUM. CIVIL SUIT	JHSK01
201	Title Arbitration Case : Title Arbitration Case	JHSK01
155	Title Eviction Suit : Title Eviction Suit	JHSK01
154	Title Mortgage Suit : Title Mortgage Suit	JHSK01
169	Title(Partition) suit : Title(Partition) suit	JHSK01
204	Title(Trade Mark) Suit : Title(Trade Mark) Suit	JHSK01
190	Transfer Petition(N) : Transfer Petition(N)	JHSK01
43	TRUST APPEAL : TRUST APPEAL	JHSK01
44	TRUST SUIT : TRUST SUIT	JHSK01
143	TS-PS/MOR/DEC/ARB : TS-PS/MOR/DEC/ARB	JHSK01
207	VIG Complaint : VIG Complaint	JHSK01
202	Vigilence Case : Vigilence Case	JHSK01
152	Anticipatory Bail Petition : Anticipatory Bail Petition	JHKH01
23	ARBITRATION CASE : ARBITRATION CASE	JHKH01
165	BAIL PETITION : BAIL PETITION	JHKH01
237	Children Cases : Children Cases	JHKH01
208	Civil Appeal : Civil Appeal	JHKH01
209	Civil Misc Appeal : Civil Misc Appeal	JHKH01
170	Civil Misc. Case : civil misc. case	JHKH01
216	Civil Misc.(Transfer Petition) : Civil Misc.(Transfer Petition)	JHKH01
234	Commercial Appeals : Commercial Appeals	JHKH01
233	Commercial suits : Commercial suits	JHKH01
239	Complain Case : Complain Case	JHKH01
213	CrAppeal : CrAppeal	JHKH01
12	CRI. APPEAL : CRI. APPEAL	JHKH01
21	CRI. CASE : CRI. CASE	JHKH01
157	Cri.(misc.)case : Criminal(Misc.)CASE	JHKH01
13	Cri. Rev : CRI. REVISION	JHKH01
212	CYBER CASE : CYBER CASE	JHKH01
8	ELECT. PETN : ELECT. PETN	JHKH01
112	EXECUITION CASES : EXECUITION CASES	JHKH01
33	FINAL DECREE : FINAL DECREE	JHKH01
164	General Register Case No. : General Register Case No.	JHKH01
161	GUARDIANSHIP : GUARDIANSHIP	JHKH01
35	INSOLVENCY : INSOLVENCY	JHKH01
109	L Acq Case : L Acq Case	JHKH01
5	LandAcqAppl : LandAcqAppl	JHKH01
146	L.a.ref : L.A.Ref	JHKH01
156	Maintainance : Maintainance	JHKH01
107	MATRIMONIAL CASE : MATRIMONIAL CASE	JHKH01
2	Misc Civil Appeal : Misc.Civil Appeal	JHKH01
225	MISC CIVIL APPLICATION : MISC CIVIL APPLICATION	JHKH01
224	MIS.CRI APPLICATION : Misc Criminal Application	JHKH01
230	MIS.CRI APPLICATION(CONFISCATION) : MIS.CRI APPLICATION(CONFISCATION)	JHKH01
218	Motor Accident Claim : Motor Accident Claim	JHKH01
217	NDPScase : NDPScase	JHKH01
15	NDPS. SPECIAL CASE : NDPS. SPECIAL CASE	JHKH01
222	NDPS SPECIAL CASE A : NDPS SPECIAL CASE A	JHKH01
223	NDPS SPECIAL CASE B : NDPS SPECIAL CASE B	JHKH01
227	NDPS SPECIAL CASE C : NDPS SPECIAL CASE C	JHKH01
229	NDPS SPECIAL CASE D : NDPS SPECIAL CASE D	JHKH01
210	OriginialSuit Adoption : OriginialSuit Adoption	JHKH01
211	POCSO : POCSO	JHKH01
108	PROBATE CASE : PROBATE CASE	JHKH01
153	Regular Bail : Regular Bail	JHKH01
226	Revoction : Revoction	JHKH01
10	Session Trial : SESSION TRIAL	JHKH01
219	SessionTrial Split Case A : SessionTrial Split Case A	JHKH01
220	Session Trial Split Case B : Session Trial Split Case B	JHKH01
221	Session Trial Split Case C : Session Trial Split Case C	JHKH01
232	Session Trial Split Case D : Session Trial Split Case D	JHKH01
235	Session Trial Split Case E : Session Trial Split Case E	JHKH01
236	Session Trial Split Case F : Session Trial Split Case F	JHKH01
166	Spl. (ndps).case : Spl (NDPS).Case	JHKH01
205	Spl Pocso Act : Spl Pocso Case	JHKH01
228	Spl Pocso Act (A) : Spl Pocso Act (A)	JHKH01
231	Spl(sc/st) A Case No : Spl(sc/st) A Case No	JHKH01
206	Spl(sc/st) Case No : Spl(SC/ST) Case no	JHKH01
161	GUARDIANSHIP : GUARDIANSHIP	JHSK05
156	Maint. Alt. Case : Maint. Alt. Case	JHSK05
191	Misc Application. : Misc Application.	JHSK05
187	Misc. Case : Misc. Case	JHSK05
190	Misc Civil Appl. : Misc Civil Application	JHSK05
188	Misc. F : Misc. F	JHSK05
189	Misc. M : Misc. M	JHSK05
171	Original Maintainance Case(N) : Original Maintainance Case(N)	JHSK05
107	Original Suit MTS(N) : Original Suit MTS (N)	JHSK05
89	ADMINISTRATIVE SUITE : ADMINISTRATIVE SUITE	JHSK02
138	APP. OF RES. SUBJUDICE U/S 10 : APP. OF RES. SUBJUDICE U/S 10	JHSK02
23	ARBITRATION CASE : ARBITRATION CASE	JHSK02
47	ARBITRATION R.D. : ARBITRATION R.D.	JHSK02
190	CIVIL EXECUTION CASE : CIVIL EXECUTION CASE	JHSK02
191	CIVIL MISC CASE : CIVIL MISC CASE	JHSK02
197	Commercial Case : Commercial Case	JHSK02
48	CONTEMPT PROCEEDINGS : CONTEMPT PROCEEDINGS	JHSK02
98	CROSS SUIT : CROSS SUIT	JHSK02
100	Declaratory  Suit : DECLARATORY SUIT	JHSK02
90	DISSOLUTION OF PATNERSHIP : DISSOLUTION OF PATNERSHIP	JHSK02
195	ECO OFF. : ECO OFF.	JHSK02
8	ELECT. PETN : ELECT. PETN	JHSK02
170	Eviction Suit : EVICTION SUIT	JHSK02
104	EVICTION SUIT(U/S BBC ACT/ U/S 111 TP ACT) : EVICTION SUIT(U/S BBC ACT/ U/S	JHSK02
112	EXECUITION CASES(N) : EXECUITION CASES(N)	JHSK02
135	INJUNCTION O-39 , R1, 2 : INJUNCTION O-39 , R1, 2	JHSK02
35	INSOLVENCY : INSOLVENCY	JHSK02
97	INTERPLEADER SUIT : INTERPLEADER SUIT	JHSK02
146	LAND ACQUISION CASE : LAND ACQUISION CASE	JHSK02
189	LAND ACQUISITION MISC CASE : LAND ACQUISITION MISC CASE	JHSK02
5	LAND AQUISITION EXECUTION CASE : LAND AQUISITION EXECUTION CASE	JHSK02
110	LUNACY/ ADOPTION/ INSOLVANCY : LUNACY/ ADOPTION/ INSOLVANCY	JHSK02
38	MESNE PROFIT : MESNE PROFIT	JHSK02
196	MIC CIV APPL(N) : MISC CIVIL APPLICATION(N)	JHSK02
171	Misc Case : Misc Case	JHSK02
193	MISC. (NON-JUDICIAL) CASES : MISC. (NON-JUDICIAL) CASES	JHSK02
173	Misc Petition : Misc Petition	JHSK02
187	Misc. Petition : Civil Misc. Petition	JHSK02
158	Mislaneous : Mislaneous	JHSK02
103	MONEY SUIT : MONEY SUIT	JHSK02
143	Original Suit(N) : Original Suit(N)	JHSK02
102	PARTION SUIT / TITLE PARTION SUIT : PARTION SUIT / TITLE PARTION S	JHSK02
45	PAUPER APPLICATION : PAUPER APPLICATION	JHSK02
17	REG. CIVIL SUIT : REG. CIVIL SUIT	JHSK02
42	RENT SUIT : RENT SUIT	JHSK02
120	RESTORATION OF SUITS 0-9 R-4 , 0-9 , R -9 : RESTORATION OF SUITS 0-9 R-4 ,	JHSK02
121	SETTING  ASIDE EXPARTE ORDER 0-9 - R -13 : SETTING  ASIDE EXPARTE ORDER 0	JHSK02
41	SMALL CAUSE SUIT : SMALL CAUSE SUIT	JHSK02
19	SPL.CIV. SUIT : SPL.CIV. SUIT	JHSK02
94	SUIT BNY PERSON OF UNSOIUND MIND : SUIT BNY PERSON OF UNSOIUND MI	JHSK02
92	SUIT BY  CORPORATION/FIRM/TRUSTEE/INDEGENT PERSON : SUIT BY  CORPORATION/FIRM/TRUS	JHSK02
93	SUIT BY MINOR : SUIT BY MINOR	JHSK02
91	SUITE BY GOVT OR PUBLIC  OFFICERS IN OFFICIAL CAPA : SUITE BY GOVT OR PUBLIC  OFFIC	JHSK02
101	SUIT FOR PERPERTUAL INJECTION : SUIT FOR PERPERTUAL INJECTION	JHSK02
99	SUIT FOR SPECIAL PERFORMANCE OF CONTRACT : SUIT FOR SPECIAL PERFORMANCE O	JHSK02
96	SUIT OF PUBLIC NAUSIANCE : SUIT OF PUBLIC NAUSIANCE	JHSK02
95	SUMMARY SUIT : SUMMARY SUIT	JHSK02
192	TITLE ARBITRATION SUIT : TITLE ARBITRATION SUIT	JHSK02
155	Title Eviction Suit : Title Eviction Suit	JHSK02
154	Title Mortgage Suit : Title Mortgage Suit	JHSK02
169	Title Partition Suit : Title Partition Suit	JHSK02
188	TITLE SUIT : TITLE SUIT	JHSK02
194	TITLE (TRADE MARK) SUIT : TITLE (TRADE MARK) SUIT	JHSK02
158	Civil Misc. Case : Miscellaneous Case	JHDG02
181	Counter Claim : Counter Claim	JHDG02
112	Execution Cases : EXECUTION CASES	JHDG02
146	Land AcquisitionCase : LAND ACQUISITION CASE	JHDG02
184	Misc. Civil Application : Misc. Civil Application	JHDG02
182	Money Exe. : Money execution	JHDG02
103	Money Suit : Money Suit	JHDG02
143	Original Suit : Original Suit	JHDG02
178	Simple Partition Suit : Title(P) Suit	JHDG02
1	TITLE APPEAL : TITLE APPEAL	JHDG02
180	Title(d)suit : Title(D)Suit	JHDG02
155	Title Eviction Suit : Title Eviction Suit	JHDG02
167	Title Execution Case : Title Execution Case	JHDG02
152	Antic.Bail Petition : Anticipatory Bail Petition	JHMP07
153	Bail Petition : Bail Petition	JHMP07
175	C.1 : C.1	JHMP07
196	C/1.s : C/1.s	JHMP07
177	C.3 : C.3	JHMP07
197	C/3.s : C/3.s	JHMP07
178	C.7 : C.7	JHMP07
210	Children Case : Children Case	JHMP07
1	CIVIL APPEAL : CIVIL APPEAL	JHMP07
166	Civil Misc.Appeal : CIVIL MISC.APPEAL	JHMP07
195	Complaint Case : Complaint Case	JHMP07
163	Confiscation Appeal : Confiscation Appeal	JHMP07
12	Criminal Appeal : CRIMINAL APPEAL	JHMP07
164	Criminal Misc. : Criminal Misc.	JHMP07
13	Criminal Revision : Criminal Revision	JHMP07
205	E.C. Cases : E.C. Cases	JHMP07
184	Election Petition : ELECTION PETITION	JHMP07
211	Electricity Act Cases : Electricity Act Cases	JHMP07
168	Eviction Appeal : EVICTION APPEAL	JHMP07
112	Execution Case : EXECUTION CASE	JHMP07
162	G.R.case : G.R.case	JHMP07
190	G.R.s : G.R. suplimentary	JHMP07
193	G.R.s1 : G.R.s1	JHMP07
194	G.R.s2 : G.R.s2	JHMP07
161	GUARDIANSHIP CASE : GUARDIANSHIP CASE	JHMP07
109	LAND ACQUI. CASE : LAND ACQUI. CASE	JHMP07
146	Letter of Admin.Case : Letter of  Administration Case	JHMP07
173	Misc.Case : Misc.Case	JHMP07
41	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHMP07
40	MISC Cri. APPLICATION : MISC Cri. APPLICATION	JHMP07
187	Misc (p). Case : Misc.Petition Case	JHMP07
169	Mot.Acci.Claims Case : Motor Accident Claims Case	JHMP07
188	N.D.P.S. Case : N.D.P.S. Case	JHMP07
203	NDPS.s : NDPS Suppl	JHMP07
207	NDPSs1 : NDPSs1	JHMP07
143	Original Suit : ORIGINAL SUIT	JHMP07
108	PROBATE CASE : PROBATE CASE	JHMP07
179	R.A.case : R.A.case	JHMP07
201	Revocation Case : Revocation Case	JHMP07
192	SC-ST Case : SC-ST Case	JHMP07
202	SC-ST.s : SC-ST.s case	JHMP07
10	Sessions Trial : SESSIONS TRIAL	JHMP07
189	Spl POCSO Case : Spl POCSO Case	JHMP07
204	Spl POCSOs : Spl POCSOs	JHMP07
191	S.T.s : S.T. supplimentary	JHMP07
198	S.T.s1 : S.T.s1	JHMP07
209	S.T.s2 : S.T.s2	JHMP07
111	Succession Cert.Case : SUCCESSION CERTIFICATE CASES	JHMP07
167	Title. P.Appeal : Title. P. Appeal	JHMP07
172	Title. P. Suit : TITLE P. SUIT	JHMP07
208	Transfer Petition : Transfer Petition	JHMP07
183	Vigilance Case : Vigilance Case	JHMP07
206	Vigilance Case(S) : Vigilance Case(S)	JHMP07
152	Anticipatory Bail : Anticipatory Bail Petition	JHMP06
153	Bail Petition : Bail Petition	JHMP06
204	Children Case : Children Case	JHMP06
1	Civil Appeal : Civil Appeal	JHMP06
2	Civil Misc. App. : Civil Misc. Appeal	JHMP06
207	Comercial Appeals : Comercial Appeals	JHMP06
206	Comercial Suit : Comercial Suit	JHMP06
205	Complaint Case : Complaint Case	JHMP06
12	Criminal Appeal : Criminal Appeal	JHMP06
13	Criminal Revision : Criminal Revision	JHMP06
176	Cri. Misc Case : cri. Misc case	JHMP06
169	Crl.Juv.Appeal/Bail Appeal/PETITION : Crl.Juv.Appeal/Bail Appeal/PET	JHMP06
189	Crl.Misc. Petition : Crl.Misc. Petition	JHMP06
164	Crl. Misc. Tr. Pet. : Crl.Misc.Transfer Petition	JHMP06
193	Cyber Crime Case : Cyber Crime Case	JHMP06
202	Cyber Crime  Split Case A : Cyber Crime  Split Case A	JHMP06
209	Cyber Crime  Split Case B : Cyber Crime  Split Case B	JHMP06
186	Drugs and  Cosmetic  act : Drugs and  Cosmetic  act	JHMP06
190	Electricity Act Cases : Electricity Act cases	JHMP06
198	Electricity Act Split case : Electricity Act Split case	JHMP06
160	Execution : Execution	JHMP06
112	Execution Cases : EXECUTION CASES	JHMP06
168	GR : G.R.	JHMP06
196	GR Split : GR Split	JHMP06
161	Guardianship Case : Guardianship Case	JHMP06
183	Letter of Admin. : Letter of Administration Case	JHMP06
188	Misc. Civil Application : Misc. Civil Application	JHMP06
187	Misc. Criminal Application : Misc. Criminal Application	JHMP06
165	Motor Acc Claim Case : Motor Accident Claims Case	JHMP06
15	NDPS Case : NDPS Case	JHMP06
197	NDPS Split Case A : NDPS Split Case A	JHMP06
208	NDPS Split Case B : NDPS Split Case B	JHMP06
143	Original Suit : Original Suit	JHMP06
36	PROBATE : PROBATE	JHMP06
170	Revocation Case : Civil Misc. Case/Petition	JHMP06
174	SC/ST Case : SC/ST Case	JHMP06
194	SC/ST Split Case A : SC/ST Split Case A	JHMP06
199	SC/ST Split Case B : SC/ST Split Case B	JHMP06
203	SC/ST Split Case C : SC/ST Split Case C	JHMP06
162	Session Trial : Session Trial	JHMP06
191	Session Trial Split Case  A : Session Trial Split Case  A	JHMP06
192	Session Trial Split Case B : Session Trial Split Case B	JHMP06
200	Session Trial Split Case C : Session Trial Split Case C	JHMP06
185	Special Pocso Case : Prev. of Child. from Sex. off.	JHMP06
195	Special Pocso Split Case A : Special Pocso Split Case A	JHMP06
201	Special Pocso Split Case B : Special Pocso Split Case B	JHMP06
111	Succ. Certifi. Case : Succession Certificate Case	JHMP06
163	Transfer Petition : Civil Misc. Transfer Petition	JHMP06
170	Civil Misc. Case : Civil Misc. Case	JHDG05
112	Execution Case : EXECUTION CASE	JHDG05
185	Maintenance Alteration Case : Maintenance Alteration Case	JHDG05
184	Misc. Civil Application : Misc. Civil Application	JHDG05
183	Misc. Criminal Application : Misc. Criminal Application	JHDG05
176	Original Maintenance Case : Original Maintenance Case	JHDG05
107	Original Suit. : ORIGINAL SUIT	JHDG05
173	Complaint Case : Complaint Case	JHDG03
187	complaint Split Case : complaint Split Case	JHDG03
176	Cri. Misc Case : cri. Misc case	JHDG03
169	Crl.Juvenile Cases : Crl.Juvenile Cases	JHDG03
172	GOCR : G.O.C.R	JHDG03
185	GOCR  SPLIT CASE A : G.O.C.R. Split Case A	JHDG03
191	GOCR  SPLIT CASE B : GOCR  SPLIT CASE B	JHDG03
168	G.R. Cases : G.R. Cases	JHDG03
186	G.R. Split Case A : G.R. Split Case A	JHDG03
188	G.R. Split Case B : G.R. Split Case B	JHDG03
190	G.R. Split Case C : G.R. Split Case C	JHDG03
184	Misc. Criminal Application : Misc. Criminal Application	JHDG03
189	Railway Act Case : Railway Act Case	JHDG03
173	Complaint Case : Complaint Case	JHMP08
187	complaint Split Case : complaint Split Case	JHMP08
176	Cri. Misc Case : cri. Misc case	JHMP08
172	GOCR : G.O.C.R	JHMP08
185	GOCR  SPLIT CASE : G.O.C.R. Split Case	JHMP08
168	G.R. Cases : G.R. Cases	JHMP08
186	G.R. Split Case A : G.R. Split Case A	JHMP08
188	G.R. Split Case B : G.R. Split Case B	JHMP08
190	G.R. Split Case C : G.R. Split Case C	JHMP08
184	Misc. Criminal Application : Misc. Criminal Application	JHMP08
189	Railway Act Case : Railway Act Case	JHMP08
120	R.G.R. : Railway General Register	JHMP08
152	Anticipatory Bail : Anticipatory Bail Petition	JHDG01
153	Bail Petition : Bail Petition	JHDG01
204	Children Case : Children Case	JHDG01
1	Civil Appeal : Civil Appeal	JHDG01
2	Civil Misc. App. : Civil Misc. Appeal	JHDG01
207	Comercial Appeals : Comercial Appeals	JHDG01
206	Comercial Suit : Comercial Suit	JHDG01
205	Complaint Case : Complaint Case	JHDG01
12	Criminal Appeal : Criminal Appeal	JHDG01
13	Criminal Revision : Criminal Revision	JHDG01
176	Cri. Misc Case : cri. Misc case	JHDG01
169	Crl.Juv.Appeal/Bail Appeal/PETITION : Crl.Juv.Appeal/Bail Appeal/PET	JHDG01
189	Crl.Misc. Petition : Crl.Misc. Petition	JHDG01
164	Crl. Misc. Tr. Pet. : Crl.Misc.Transfer Petition	JHDG01
193	Cyber Crime Case : Cyber Crime Case	JHDG01
202	Cyber Crime  Split Case A : Cyber Crime  Split Case A	JHDG01
209	Cyber Crime  Split Case B : Cyber Crime  Split Case B	JHDG01
186	Drugs and  Cosmetic  act : Drugs and  Cosmetic  act	JHDG01
210	Drugs and  Cosmetic  act Split A : Drugs and  Cosmetic  act Split A	JHDG01
190	Electricity Act Cases : Electricity Act cases	JHDG01
198	Electricity Act Split case : Electricity Act Split case	JHDG01
160	Execution : Execution	JHDG01
112	Execution Cases : EXECUTION CASES	JHDG01
168	GR : G.R.	JHDG01
196	GR Split : GR Split	JHDG01
161	Guardianship Case : Guardianship Case	JHDG01
183	Letter of Admin. : Letter of Administration Case	JHDG01
188	Misc. Civil Application : Misc. Civil Application	JHDG01
187	Misc. Criminal Application : Misc. Criminal Application	JHDG01
165	Motor Acc Claim Case : Motor Accident Claims Case	JHDG01
15	NDPS Case : NDPS Case	JHDG01
197	NDPS Split Case A : NDPS Split Case A	JHDG01
208	NDPS Split Case B : NDPS Split Case B	JHDG01
143	Original Suit : Original Suit	JHDG01
36	PROBATE : PROBATE	JHDG01
170	Revocation Case : Civil Misc. Case/Petition	JHDG01
174	SC/ST Case : SC/ST Case	JHDG01
194	SC/ST Split Case A : SC/ST Split Case A	JHDG01
199	SC/ST Split Case B : SC/ST Split Case B	JHDG01
203	SC/ST Split Case C : SC/ST Split Case C	JHDG01
162	Session Trial : Session Trial	JHDG01
191	Session Trial Split Case  A : Session Trial Split Case  A	JHDG01
192	Session Trial Split Case B : Session Trial Split Case B	JHDG01
200	Session Trial Split Case C : Session Trial Split Case C	JHDG01
185	Special Pocso Case : Prev. of Child. from Sex. off.	JHDG01
195	Special Pocso Split Case A : Special Pocso Split Case A	JHDG01
201	Special Pocso Split Case B : Special Pocso Split Case B	JHDG01
111	Succ. Certifi. Case : Succession Certificate Case	JHDG01
163	Transfer Petition : Civil Misc. Transfer Petition	JHDG01
180	C.g. : COMPLAINT WISE GOVT	JHPL03
181	Comp.cases : complaint cases.	JHPL03
178	Complaint Case : COMPLAINT CASE	JHPL03
179	Comp.w.f. : complaint wise Forest	JHPL03
159	Cr. Case Complaint (O) : Cr. Case Complaint (O)	JHPL03
142	Cr. Case Complaint (P) : Cr. Case Complaint (P)	JHPL03
24	CRI. BAIL APPLN. : CRI. BAIL APPLN.	JHPL03
21	CRI. CASE : CRI. CASE	JHPL03
157	Crl. Misc. Case : Criminal Mislaneous	JHPL03
182	C.w.e : COMPLAINT WISE EXCISE	JHPL03
80	DISTRESS WARRENT : DISTRESS WARRENT	JHPL03
147	Domestic Violence Act 2005 : Domestic Violence Act 2005	JHPL03
16	E.C.Case : E.C.ACT.SPL.CASE	JHPL03
32	E.S.I. ACT  CASE : E.S.I. ACT  CASE	JHPL03
149	Excise Act : Excise Act	JHPL03
177	G.R. Cases : GENERAL REGISTER CASE	JHPL03
198	Juvenile Cases : Juvenile Cases	JHPL03
196	MISC. CRI. APPLICATION : MISC. CRI. APPLICATION	JHPL03
30	OTHER MISC. CRI. APPLN. : OTHER MISC. CRI. APPLN.	JHPL03
194	Police Act Cases : Police Act Cases	JHPL03
191	Railway Act Case : RALWAY ACT	JHPL03
20	REG. CRI. CASE : REG. CRI. CASE	JHPL03
153	Regular Bail : Regular Bail	JHPL03
31	REVIEW APPLICATION : REVIEW APPLICATION	JHPL03
183	R.p.act : RAILWAY PROTECTION CASE	JHPL03
197	Special POCSO Case : Special POCSO Case	JHPL03
25	SPL. CRI. MA : SPL. CRI. MA	JHPL03
148	Weight and Measurement Act : Weight and Measurement Act	JHPL03
160	Weight measurement Act 1985 : Weight  measurement Act 1985	JHPL03
89	ADMINISTRATIVE SUITE : ADMINISTRATIVE SUITE	JHPL04
138	APP. OF RES. SUBJUDICE U/S 10 : APP. OF RES. SUBJUDICE U/S 10	JHPL04
23	ARBITRATION CASE : ARBITRATION CASE	JHPL04
47	ARBITRATION R.D. : ARBITRATION R.D.	JHPL04
169	civil execution case : civil execution case	JHPL04
170	civil misc. case : civil misc. case	JHPL04
105	Civil Misc Case : Civil Misc Case	JHPL04
48	CONTEMPT PROCEEDINGS : CONTEMPT PROCEEDINGS	JHPL04
98	CROSS SUIT : CROSS SUIT	JHPL04
100	DECLARATIVCE SUIT : DECLARATIVCE SUIT	JHPL04
90	DISSOLUTION OF PATNERSHIP : DISSOLUTION OF PATNERSHIP	JHPL04
8	ELECT. PETN : ELECT. PETN	JHPL04
104	EVICTION SUIT : EVICTION SUIT(U/S BBC ACT/ U/S	JHPL04
112	Execution Case : Execution	JHPL04
33	FINAL DECREE : FINAL DECREE	JHPL04
37	GUARDIAN AND WARDS CASE : GUARDIAN and  WARDS CASE	JHPL04
135	INJUNCTION O-39 : INJUNCTION O-39	JHPL04
35	INSOLVENCY : INSOLVENCY	JHPL04
97	INTERPLEADER SUIT : INTERPLEADER SUIT	JHPL04
109	LAND ACQUISTAION CASES : LAND ACQUISTAION CASES	JHPL04
5	LAND REF. : LAND REF.	JHPL04
110	LUNACY/ ADOPTION/ INSOLVANCY : LUNACY/ ADOPTION/ INSOLVANCY	JHPL04
38	MESNE PROFIT : MESNE PROFIT	JHPL04
133	MESNS PROFIT 0-34 , R-10A : MESNS PROFIT 0-34 , R-10A	JHPL04
192	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHPL04
158	Miscellanous : Miscellanous	JHPL04
103	MONEY SUIT : MONEY SUIT	JHPL04
191	Original Suit : Orginal Suit	JHPL04
102	PARTITION SUIT / TITLE SUIT : PARTITION SUIT /TITLE SUIT	JHPL04
45	PAUPER APPLICATION : PAUPER APPLICATION	JHPL04
17	REG. CIVIL SUIT : REG. CIVIL SUIT	JHPL04
28	REGULAR PETITION : REGULAR PETITION	JHPL04
42	RENT SUIT : RENT SUIT	JHPL04
120	RESTORATION OF SUITS 0-9 R-4 : RESTORATION OF SUITS 0-9 R-4	JHPL04
121	SETTING  ASIDE EXPARTE ORDER 0 : SETTING  ASIDE EXPARTE ORDER 0	JHPL04
41	SMALL CAUSE SUIT : SMALL CAUSE SUIT	JHPL04
19	SPL.CIV. SUIT : SPL.CIV. SUIT	JHPL04
29	SPL. DARKHAST : SPL. DARKHAST	JHPL04
145	SUCCESSION CASES : SUCCESSION	JHPL04
94	SUIT BNY PERSON OF UNSOIUND MIND : SUIT BNY PERSON OF UNSOIUND MI	JHPL04
92	SUIT BY  CORPORATION/FIRM/TRUSTEE/INDEGENT PERSON : SUIT BY  CORPORATION/FIRM/TRUS	JHPL04
93	SUIT BY MINOR : SUIT BY MINOR	JHPL04
91	SUITE BY GOVT OR PUBLIC  OFFICERS : SUITE BY GOVT OR PUBLIC  OFFIC	JHPL04
101	SUIT FOR PERPERTUAL INJECTION : SUIT FOR PERPERTUAL INJECTION	JHPL04
99	SUIT FOR SPECIAL PERFORMANCE OF CONTRACT : SUIT FOR SPECIAL PERFORMANCE O	JHPL04
96	SUIT OF PUBLIC NAUSIANCE : SUIT OF PUBLIC NAUSIANCE	JHPL04
22	SUMMARY CASE : SUMMARY CASE	JHPL04
95	SUMMARY SUIT : SUMMARY SUIT	JHPL04
175	Title Arbitration Suit : Title Arbitration Suit	JHPL04
154	Title Mortgage Suit : Title Mortgage Suit	JHPL04
143	Title Suit : Title Suit	JHPL04
89	ADMINISTRATIVE SUITE : ADMINISTRATIVE SUITE	JHPL02
23	Arbitration Case : ARBITRATION CASE	JHPL02
47	ARBITRATION R.D. : ARBITRATION R.D.	JHPL02
105	Civil Misc. Case : CIVIL MISC CASES	JHPL02
195	Commercial(Arbitration) Case : Commercial(Arbitration) Case	JHPL02
194	Commercial Execution Case : Commercial Execution Case	JHPL02
193	Commercial(Suit) Case : Commercial(Suit) Case	JHPL02
48	CONTEMPT PROCEEDINGS : CONTEMPT PROCEEDINGS	JHPL02
98	CROSS SUIT : CROSS SUIT	JHPL02
100	DECLARATIVCE SUIT : DECLARATIVCE SUIT	JHPL02
90	DISSOLUTION OF PATNERSHIP : DISSOLUTION OF PATNERSHIP	JHPL02
104	EVICTION SUIT : EVICTION SUIT	JHPL02
112	Execution Case : EXECUITION CASES	JHPL02
35	INSOLVENCY : INSOLVENCY	JHPL02
97	INTERPLEADER SUIT : INTERPLEADER SUIT	JHPL02
109	Land Acquisition Case : LAND ACQUISTAION CASES	JHPL02
110	LUNACY/ ADOPTION/ INSOLVANCY : LUNACY/ ADOPTION/ INSOLVANCY	JHPL02
192	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHPL02
188	Miscellaneous Case : Misc.Case	JHPL02
158	Mislaneous : Mislaneous	JHPL02
103	MONEY SUIT : MONEY SUIT	JHPL02
191	Original Suit : ORIGINAL SUIT	JHPL02
102	Partition Suit : PARTION SUIT	JHPL02
45	PAUPER APPLICATION : PAUPER APPLICATION	JHPL02
17	REG. CIVIL SUIT : REG. CIVIL SUIT	JHPL02
42	RENT SUIT : RENT SUIT	JHPL02
120	RESTORATION OF SUITS 0-9 R-4 , 0-9 , R -9 : RESTORATION OF SUITS 0-9 R-4 ,	JHPL02
121	SETTING  ASIDE EXPARTE ORDER 0-9 - R -13 : SETTING  ASIDE EXPARTE ORDER 0	JHPL02
41	SMALL CAUSE SUIT : SMALL CAUSE SUIT	JHPL02
19	SPL.CIV. SUIT : SPL.CIV. SUIT	JHPL02
111	Succession Case : SUCCESSION CERTIFICATE CASES	JHPL02
94	SUIT BNY PERSON OF UNSOIUND MIND : SUIT BNY PERSON OF UNSOIUND MI	JHPL02
92	SUIT BY  CORPORATION/FIRM/TRUSTEE/INDEGENT PERSON : SUIT BY  CORPORATION/FIRM/TRUS	JHPL02
93	SUIT BY MINOR : SUIT BY MINOR	JHPL02
91	SUITE BY GOVT OR PUBLIC  OFFICERS IN OFFICIAL CAPA : SUITE BY GOVT OR PUBLIC  OFFIC	JHPL02
101	SUIT FOR PERPERTUAL INJECTION : SUIT FOR PERPERTUAL INJECTION	JHPL02
99	SUIT FOR SPECIAL PERFORMANCE OF CONTRACT : SUIT FOR SPECIAL PERFORMANCE O	JHPL02
96	SUIT OF PUBLIC NAUSIANCE : SUIT OF PUBLIC NAUSIANCE	JHPL02
95	SUMMARY SUIT : SUMMARY SUIT	JHPL02
190	Civ. Misc : Civ. Misc	JHPL05
155	Title Eviction Suit : Title Eviction Suit	JHPL02
154	Title Mortgage Suit : Title Mortgage Suit	JHPL02
143	Title Suit : Title Suit	JHPL02
152	Anticipatory Bail Petition : Anticipatory Bail Petition	JHPL01
23	ARBITRATION CASE : ARBITRATION CASE	JHPL01
151	Bail Petition : Bail Petition	JHPL01
198	Civil Appeal : Civil Appeal	JHPL01
168	Civil Misc.appeal : Civil Miscellaneous Appeal	JHPL01
2	Civil Misc. Appeal : Civil Misc Appeal	JHPL01
209	Commercial Arbitration Case : Commercial Arbitration Case	JHPL01
208	Commercial Execution Case : Commercial Execution Case	JHPL01
205	Commercial(Suit) Case : Commercial(Suit) Case	JHPL01
178	Comp.case : COMPLAINT CASES	JHPL01
12	Criminal Appeal : CRIMINAL APPEAL	JHPL01
157	Criminal Misc. : Criminal Mislaneous	JHPL01
13	Criminal Revision : Criminal Revision	JHPL01
213	Cyber Crime Case(c) : Cyber Crime Case(c)	JHPL01
203	Cyber Crime Cases : Cyber Crime Cases	JHPL01
204	Drugs and Cosmetic Special Case : Drugs and Cosmetic Special Case	JHPL01
212	E.C.Case : E.C.Case	JHPL01
173	Elect.case : ELECTRICITY CASE	JHPL01
8	Electricity Act cases : Electricity Act cases	JHPL01
163	Eviction Appeal : eviction appeal	JHPL01
112	EXECUITION CASES : EXECUITION CASES	JHPL01
33	FINAL DECREE : FINAL DECREE	JHPL01
177	G R Case : GENERAL REGISTER CASE	JHPL01
161	Guardianship Cases : GUARDIANSHIP CASES	JHPL01
35	INSOLVENCY : INSOLVENCY	JHPL01
211	Juvenile Cases : Juvenile Cases	JHPL01
146	L.A : L.A	JHPL01
109	LAND ACQUISITION CASES : LAND ACQUISITION CASES	JHPL01
5	LAND REF. : Land Acq Appl	JHPL01
190	Letter of Administration Case : LETTER OF ADMINISTRA	JHPL01
7	L.R  DKST. : Cyber Case	JHPL01
210	Maint. Alt. Case : Maint. Alt. Case	JHPL01
201	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHPL01
200	MISC CRI. APPLICATION : MISC CRI. APPLICATION	JHPL01
140	MONEY APPEAL : MONEY APPEAL	JHPL01
170	Motor Accident Claims Cases : MOTOR ACCIDENT CLAIMS CASES	JHPL01
15	NDPS.  CASE : NDPS.  CASE	JHPL01
156	Original Maintainance Case : Original Maintainance Case	JHPL01
202	Original Maint Case : Original Maint Case	JHPL01
191	Original Suit : ORIGINAL SUIT	JHPL01
6	Partition Appeal : Partition Appeal	JHPL01
102	Partition Suit : PARTITION SUIT	JHPL01
196	Pocso : Pocso	JHPL01
108	PROBATE CASE : PROBATE CASE	JHPL01
153	Regular Bail : Regular Bail	JHPL01
105	Revocation Case : MISC CASES a) O-9 , R-4  b) O-	JHPL01
175	Sc/st : SC/ST Cases	JHPL01
10	Sessions Trial : SESSION Trials	JHPL01
193	Special POCSO Case : POCSO CASES	JHPL01
206	Special POCSO Case(C) : Special POCSO Case(C)	JHPL01
39	Succession CASE : Succession Certificate Case	JHPL01
111	Succession Certificate Case : SUCESSION CERTIFICATE CASES	JHPL01
81	SUM. CIVIL SUIT : SUM. CIVIL SUIT	JHPL01
1	TITLE APPEAL : TITLE APPEAL	JHPL01
192	TITLE EV APPEAL : TITLE EVICTION APPEAL	JHPL01
155	Title Eviction Suit : Title Eviction Suit	JHPL01
154	Title Mortgage Suit : Title Mortgage Suit	JHPL01
143	Title Suit : Title Suit	JHPL01
199	Transfer Petition : Transfer Petition	JHPL01
43	TRUST APPEAL : TRUST APPEAL	JHPL01
44	TRUST SUIT : TRUST SUIT	JHPL01
176	Vigilance Case : Special Case Vigilance	JHPL01
207	Vigilance Case(c) : Vigilamce Case(c)	JHPL01
148	Weight and Measurement Act : Weight and Measurement Act	JHPL01
111	SUCCESSION CERTIFICATE CASES : SUCCESSION CERTIFICATE CASES	JHKH01
154	Title Mortgage Suit : Title Mortgage Suit	JHKH01
143	Title Suit : Title Suit	JHKH01
174	T(p)suit : Title(partion)Suit	JHKH01
238	TRANSFER PETITION : TRANSFER PETITION	JHKH01
148	Weight and Measurement Act : Weight and Measurement Act	JHKH01
112	Execution : Execution	JHPL05
161	Guardianship : Guardianship	JHPL05
192	Maint. Alt. Case : Maint. Alt. Case	JHPL05
107	MATRIMONIAL CASE : MATRIMONIAL CASE	JHPL05
105	Misc. F : Misc. F	JHPL05
188	Misc.M : Misc.M	JHPL05
156	Original Maint Case : Original Maint Case	JHPL05
191	Original Suit(MTS) : Original Suit(MTS)	JHPL05
221	Complain suplimentry : Complain suplimentry	JHSD03
170	Complaint Case : Complaint Case	JHSD03
159	Cr. Case Complaint (O) : Cr. Case Complaint (O)	JHSD03
142	Cr. Case Complaint (P) : Cr. Case Complaint (P)	JHSD03
24	CRI. BAIL APPLN. : CRI. BAIL APPLN.	JHSD03
21	CRI. CASE : CRI. CASE	JHSD03
171	Crl. Misc. Case : Criminal Misc CaseMaintainence	JHSD03
147	Domestic Violence Act 2005 : Domestic Violence Act 2005	JHSD03
172	G.o.c. : Government Official Complain	JHSD03
223	G.O.C. s : G.O.C. s	JHSD03
162	G.R : G.R	JHSD03
163	GR. S : GR. S	JHSD03
224	G.R. S/1 : G.R. S/1	JHSD03
229	G.R. S/1 A : G.R. S/1 A	JHSD03
233	GR. S/1 B : GR. S/1 B	JHSD03
228	G.R. S/2 : G.R. S/2	JHSD03
225	GR.S/2 : GR.S/2	JHSD03
240	GR S/3 : GR S/3	JHSD03
38	MESNE PROFIT : MESNE PROFIT	JHSD03
165	MISC. CRI. APPLICATION : MISC. CRI. APPLICATION	JHSD03
227	Non F.I.R. : Non F.I.R.	JHSD03
30	OTHER MISC. CRI. APPLN. : OTHER MISC. CRI. APPLN.	JHSD03
153	Regular Bail : Regular Bail	JHSD03
31	REVIEW APPLICATION : REVIEW APPLICATION	JHSD03
25	SPL. CRI. MA : SPL. CRI. MA	JHSD03
148	Weight and Measurement Act : Weight and Measurement Act	JHSD03
160	Weight and measurement Act 1985 : Weight and measurement Act 198	JHSD03
89	ADMINISTRATIVE SUITE : ADMINISTRATIVE SUITE	JHSD04
139	APP. OF RESJUDICATE U/S 11 CPC : APP. OF RESJUDICATE U/S 11 CPC	JHSD04
138	APP. OF RES. SUBJUDICE U/S 10 : APP. OF RES. SUBJUDICE U/S 10	JHSD04
23	ARBITRATION CASE : ARBITRATION CASE	JHSD04
47	ARBITRATION R.D. : ARBITRATION R.D.	JHSD04
184	Civ. Misc. : Civil Miscellaneous	JHSD04
98	CROSS SUIT : CROSS SUIT	JHSD04
100	DECLARATIVCE SUIT : DECLARATIVCE SUIT	JHSD04
90	DISSOLUTION OF PATNERSHIP : DISSOLUTION OF PATNERSHIP	JHSD04
80	DISTRESS WARRENT : DISTRESS WARRENT	JHSD04
8	ELECT. PETN : ELECT. PETN	JHSD04
104	EVICTION SUIT : EVICTION SUIT	JHSD04
112	EXECUITION CASES : EXECUITION CASES	JHSD04
33	FINAL DECREE : FINAL DECREE	JHSD04
37	GUARDIAN and WARDS CASE : GUARDIAN and WARDS CASE	JHSD04
135	INJUNCTION O-39 , R1, 2 : INJUNCTION O-39 , R1, 2	JHSD04
35	INSOLVENCY : INSOLVENCY	JHSD04
97	INTERPLEADER SUIT : INTERPLEADER SUIT	JHSD04
109	LAND ACQUISTAION CASES : LAND ACQUISTAION CASES	JHSD04
5	LAND REF. : LAND REF.	JHSD04
110	LUNACY/ ADOPTION/ INSOLVANCY : LUNACY/ ADOPTION/ INSOLVANCY	JHSD04
38	MESNE PROFIT : MESNE PROFIT	JHSD04
105	MISC CASES a) O-9 , R-4  b) O-9, R-9 c) U/S 47CPC : MISC CASES a) O-9 , R-4  b) O-	JHSD04
215	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHSD04
143	Original Suit : Original  Suit	JHSD04
213	Partition Proceeding. : Partition Proceeding.	JHSD04
45	PAUPER APPLICATION : PAUPER APPLICATION	JHSD04
17	REG. CIVIL SUIT : REG. CIVIL SUIT	JHSD04
28	REGULAR PETITION : REGULAR PETITION	JHSD04
42	RENT SUIT : RENT SUIT	JHSD04
120	RESTORATION OF SUITS 0-9 R-4 , 0-9 , R -9 : RESTORATION OF SUITS 0-9 R-4 ,	JHSD04
121	SETTING  ASIDE EXPARTE ORDER 0-9 - R -13 : SETTING  ASIDE EXPARTE ORDER 0	JHSD04
41	SMALL CAUSE SUIT : SMALL CAUSE SUIT	JHSD04
29	SPL. DARKHAST : SPL. DARKHAST	JHSD04
39	SUCCESSION. : SUCCESSION.	JHSD04
94	SUIT BNY PERSON OF UNSOIUND MIND : SUIT BNY PERSON OF UNSOIUND MI	JHSD04
92	SUIT BY  CORPORATION/FIRM/TRUSTEE/INDEGENT PERSON : SUIT BY  CORPORATION/FIRM/TRUS	JHSD04
93	SUIT BY MINOR : SUIT BY MINOR	JHSD04
91	SUITE BY GOVT OR PUBLIC  OFFICERS IN OFFICIAL CAPA : SUITE BY GOVT OR PUBLIC  OFFIC	JHSD04
101	SUIT FOR PERPERTUAL INJECTION : SUIT FOR PERPERTUAL INJECTION	JHSD04
99	SUIT FOR SPECIAL PERFORMANCE OF CONTRACT : SUIT FOR SPECIAL PERFORMANCE O	JHSD04
96	SUIT OF PUBLIC NAUSIANCE : SUIT OF PUBLIC NAUSIANCE	JHSD04
22	SUMMARY CASE : SUMMARY CASE	JHSD04
95	SUMMARY SUIT : SUMMARY SUIT	JHSD04
154	Title Mortgage Suit : Title Mortgage Suit	JHSD04
102	TITLE PARTION SUIT : TITLE PARTION S	JHSD04
89	ADMINISTRATIVE SUITE : ADMINISTRATIVE SUITE	JHSD02
139	APP. OF RESJUDICATE U/S 11 CPC : APP. OF RESJUDICATE U/S 11 CPC	JHSD02
138	APP. OF RES. SUBJUDICE U/S 10 : APP. OF RES. SUBJUDICE U/S 10	JHSD02
23	ARBITRATION CASE : ARBITRATION CASE	JHSD02
47	ARBITRATION R.D. : ARBITRATION R.D.	JHSD02
224	Caveat Case : Caveat Case	JHSD02
223	Civil Misc. Case : Civil Misc. Case	JHSD02
225	Commercial Suit : Commercial Suit	JHSD02
48	CONTEMPT PROCEEDINGS : CONTEMPT PROCEEDINGS	JHSD02
98	CROSS SUIT : CROSS SUIT	JHSD02
100	DECLARATIVCE SUIT : DECLARATIVCE SUIT	JHSD02
90	DISSOLUTION OF PATNERSHIP : DISSOLUTION OF PATNERSHIP	JHSD02
8	ELECT. PETN : ELECT. PETN	JHSD02
104	EVICTION SUIT(U/S BBC ACT/ U/S 111 TP ACT) : EVICTION SUIT(U/S BBC ACT/ U/S	JHSD02
112	EXECUITION CASES : EXECUITION CASES	JHSD02
135	INJUNCTION O-39 , R1, 2 : INJUNCTION O-39 , R1, 2	JHSD02
35	INSOLVENCY : INSOLVENCY	JHSD02
97	INTERPLEADER SUIT : INTERPLEADER SUIT	JHSD02
109	LAND ACQUISTAION CASES : LAND ACQUISTAION CASES	JHSD02
5	LAND REF. : LAND REF.	JHSD02
110	LUNACY/ ADOPTION/ INSOLVANCY : LUNACY/ ADOPTION/ INSOLVANCY	JHSD02
38	MESNE PROFIT : MESNE PROFIT	JHSD02
105	MISC CASES a) O-9 , R-4  b) O-9, R-9 c) U/S 47CPC : MISC CASES a) O-9 , R-4  b) O-	JHSD02
215	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHSD02
222	Original Suit : Original Suit	JHSD02
102	PARTION SUIT / TITLE PARTION SUIT : PARTION SUIT / TITLE PARTION S	JHSD02
45	PAUPER APPLICATION : PAUPER APPLICATION	JHSD02
17	REG. CIVIL SUIT : REG. CIVIL SUIT	JHSD02
42	RENT SUIT : RENT SUIT	JHSD02
120	RESTORATION OF SUITS 0-9 R-4 , 0-9 , R -9 : RESTORATION OF SUITS 0-9 R-4 ,	JHSD02
121	SETTING  ASIDE EXPARTE ORDER 0-9 - R -13 : SETTING  ASIDE EXPARTE ORDER 0	JHSD02
41	SMALL CAUSE SUIT : SMALL CAUSE SUIT	JHSD02
19	SPL.CIV. SUIT : SPL.CIV. SUIT	JHSD02
92	SUIT BY  CORPORATION/FIRM/TRUSTEE/INDEGENT PERSON : SUIT BY  CORPORATION/FIRM/TRUS	JHSD02
93	SUIT BY MINOR : SUIT BY MINOR	JHSD02
91	SUITE BY GOVT OR PUBLIC  OFFICERS IN OFFICIAL CAPA : SUITE BY GOVT OR PUBLIC  OFFIC	JHSD02
101	SUIT FOR PERPERTUAL INJECTION : SUIT FOR PERPERTUAL INJECTION	JHSD02
99	SUIT FOR SPECIAL PERFORMANCE OF CONTRACT : SUIT FOR SPECIAL PERFORMANCE O	JHSD02
96	SUIT OF PUBLIC NAUSIANCE : SUIT OF PUBLIC NAUSIANCE	JHSD02
1	Title Arbitration Suit : TITLE APPEAL	JHSD02
154	Title Mortgage Suit : Title Mortgage Suit	JHSD02
143	Title Suit : Title Suit	JHSD02
152	Anticipatory Bail : Anticipatory Bail Petition	JHSD01
23	ARBITRATION CASE : ARBITRATION CASE	JHSD01
153	Bail Petition : Bail petition	JHSD01
1	CIVIL APPEAL : CIVIL  APPEAL	JHSD01
238	CIvil Misc. Appeal : Civil Misc. Appeal	JHSD01
243	Civ.Misc.Case : Civil Misc. Case	JHSD01
266	Commercial Appeal : Commercial Appeal	JHSD01
261	Commercial Case : Commercial Case	JHSD01
265	Commercial Suit : Commercial Suit	JHSD01
262	Comm. Execution : Comm. Execution	JHSD01
169	Complaint Case : COMPENSATION CASE	JHSD01
12	CRI. APPEAL : CRI. APPEAL	JHSD01
157	Criminal Mislaneous Case : Criminal Mislaneous	JHSD01
13	Cri Rev : Cri. Rev.	JHSD01
240	Crl. Misc. Case : Criminal Misc. Case	JHSD01
253	Electricity Act Case : Electricity Act Case	JHSD01
112	EXECUITION CASES : EXECUITION CASES	JHSD01
33	FINAL DECREE : FINAL DECREE	JHSD01
162	G.r. : G.r.	JHSD01
161	GUARDIANSHIP : GUARDIANSHIP	JHSD01
35	INSOLVENCY : INSOLVENCY	JHSD01
109	LAND ACQUISTAION CASES : LAND ACQUISTAION CASES	JHSD01
5	LAND REF. : LAND REF.	JHSD01
107	MATRIMONIAL CASE : MATRIMONIAL CASE	JHSD01
215	Misc Civil Application : Misc Civil Application	JHSD01
165	Misc Criminal Application : Misc Criminal Application	JHSD01
174	Motor Acc.Claim Case : Motor  Acc. Claim Case	JHSD01
15	NDPS  CASE : NDPS  CASE	JHSD01
260	NDPS CASE S-I : NDPS CASE S-I	JHSD01
228	NDPS S. CASE s. : NDPS S. CASE s.	JHSD01
264	NDPS (S-II) : NDPS (S-II)	JHSD01
227	Non F.I.R. : Non F.I.R.	JHSD01
250	Original Maintenance Case : Original Maintenance Case	JHSD01
249	Original Suit : Original Suit	JHSD01
236	P.O.C.S.O. : P.O.C.S.O.	JHSD01
258	P.O.C.S.O (S) : P.O.C.S.O (S)	JHSD01
263	P.O.C.S.O (S-I) : P.O.C.S.O (S-I)	JHSD01
108	PROBATE CASE : PROBATE CASE	JHSD01
229	Regular Bail : Regular Bail	JHSD01
254	SC / ST CASE A : SC / ST CASE A	JHSD01
255	SC/ ST CASE S : SC/ ST CASE S	JHSD01
34	SC/ ST CASES : SC/ ST CASES	JHSD01
246	Sessional Trail A(S) : Sessional Trail A(S)	JHSD01
10	Sessions Trial : SESSIONS TRIAL	JHSD01
223	Sessions Trial A : Sessions Trial A	JHSD01
245	Sessions Trial A-1 : Sessions Trial A-1	JHSD01
224	Sessions Trial B : Sessions Trial B	JHSD01
225	Sessions Trial C : Sessions Trial C	JHSD01
251	Sessions Trial C-1 : Sessions Trial C-1	JHSD01
252	Sessions Trial D : Sessions Trial D	JHSD01
226	Sessions Trial S : Sessions Trial S	JHSD01
247	Sessions Trial (S-1) : Sessions Trial (S-1)	JHSD01
248	Sessions Trial S II : Sessions Trial S II	JHSD01
257	Session Trial A-II : Session Trial A-II	JHSD01
244	Session Trial B-1 : Session Trial B-1	JHSD01
193	Special Case : Special Case	JHSD01
214	Special Case (p.o.c.s.o.) : Special Case (P.O.C.S.O.)	JHSD01
256	Special Children Case : Special Children Case	JHSD01
39	Succession. : Succession.	JHSD01
111	SUCESSION CERTIFICATE CASES : SUCESSION CERTIFICATE CASES	JHSD01
81	SUM. CIVIL SUIT : SUM. CIVIL SUIT	JHSD01
155	Title Eviction Suit : Title Eviction Suit	JHSD01
154	Title Mortgage Suit : Title Mortgage Suit	JHSD01
259	TRANSFER PETITION : TRANSFER PETITION	JHSD01
43	TRUST APPEAL : TRUST APPEAL	JHSD01
44	TRUST SUIT : TRUST SUIT	JHSD01
167	TS-PS/MOR/DEC/ARB : TS-PS/MOR/DEC/ARB	JHSD01
148	Weight and Measurement Act : Weight and Measurement Act	JHSD01
17	Bail Petition : Bail Petition	JHLT03
60	BOC ACT : BOC ACT	JHLT03
106	C.F Splt.A : Complaint Split Up A	JHLT03
50	Coal Mines Act : Coal Mines Act	JHLT03
101	Comlaint Case Spl. : Comlaint Case Spl.	JHLT03
66	Comp. forest Case : Comp. forest Case	JHLT03
107	Comp. Forest SPlt.B : Comp. Forest SPlt.B	JHLT03
58	COMPLAINT CASE : COMPLAINT CASE	JHLT03
103	Complaint Case Spl. : Complaint Case Spl.	JHLT03
100	Complaint Forest Spl. : Complaint Forest Spl.	JHLT03
67	Complaint Govt. Case : Complaint  Government Case	JHLT03
104	Comp. supl. : Complain supplentray Case	JHLT03
68	Compt. Excise Case : Compt. Excise Case	JHLT03
94	Contract Labour Act : Contract Labour Act	JHLT03
91	Cri Bail. Appln : Cri Bail. Appln	JHLT03
38	Cri. Exec. Cases : Execution Cases	JHLT03
36	Cri. Misc. Case : Cri. Misc. Case	JHLT03
89	Distress Warrent : Distress Warrent	JHLT03
73	Domestic Voilence Act : Domestic Voilence Act Cases	JHLT03
86	Employees Provident Fund : Employees Provident Fund	JHLT03
47	Employement Exchange : Employement Exchange	JHLT03
1	Equal Remuneration Act Case : Equal Remuneration Act Case	JHLT03
76	ESI Act  Case : ESI Act  Case	JHLT03
34	Excise Act : Excise Act	JHLT03
31	Factory Act : Factory Act	JHLT03
109	G.R Case (D) : G.R Case (D)	JHLT03
65	G.R. CASES : G.R. CASES	JHLT03
110	G.R Case Supl. E : G.R Case Supl. E	JHLT03
16	GR Pre Cog : GR Pre Cog	JHLT03
99	G.R (S) : G.R (S)	JHLT03
112	G.R (S)-I : G.R (S)-I	JHLT03
96	G.R.Splt.A : G.R.Splt.A	JHLT03
97	G.R.Splt. B : G.R.Splt. B	JHLT03
108	G. R Sup C : G. R Sup C	JHLT03
32	Industrial Disputes Act : Industrial Disputes Act	JHLT03
111	Juvenile Case : Juvenile Case	JHLT03
51	Minimum Wages Act : Minimum Wages Act	JHLT03
105	MISC. CRI. APPLICATION : MISC. CRI. APPLICATION	JHLT03
33	Misc Petition : Misc Petition	JHLT03
21	Other Misc Crl.Appln : Other Misc Crl.Appln	JHLT03
57	Payment of Wage Act : Payment of Wage Act	JHLT03
62	Preventation Of Food Adultati : Preventation Of Food Adultati	JHLT03
15	Prevention of Cruelty Against : Prevention of Cruelty Against	JHLT03
55	Rail Case : Rail Case	JHLT03
4	Reg Cri Case : Reg Cri Case	JHLT03
18	Regular Bail : Regular Bail	JHLT03
84	Review Application : Review Application	JHLT03
5	Spl Cri MA : Spl Cri MA	JHLT03
53	Under Railway Act : Under Railway Act	JHLT03
2	Vigilance PS Case : Vigilance PS Case	JHLT03
25	Weight And Measurement Act 1985 : Weight And Measurement Act 198	JHLT03
60	Administrative Suite : Administrative Suite	JHLT04
1	App. of reg Subjudice u/s 10 : App. of reg Subjudice u/s 10	JHLT04
39	Arb : ARBITRATION	JHLT04
20	ARBITRATION R.D : ARBITRATION R.D	JHLT04
61	Civil Misc. Cases : Civil  Misc. Cases	JHLT04
100	Commercial Suit : Commercial Suit	JHLT04
21	Contempt Proceedings : Contempt Proceedings	JHLT04
18	Cross Suit : Cross Suit	JHLT04
31	Declarativce Suit : Declarativce Suit	JHLT04
5	Dissolution of Patnership : Dissolution of Patnership	JHLT04
94	Election Petition Suit : Election Petition Suit	JHLT04
50	Elect Petn : Elect Petn	JHLT04
86	Eviction Suits : Eviction Suits	JHLT04
48	Eviction Suit (u/s BBc Act U/S ) : Eviction Suit (u/s BBc Act U/S	JHLT04
97	Execution Case : Execution Case	JHLT04
38	Execution Case. : Execution Case.	JHLT04
66	Final Decree : Final Decree	JHLT04
67	Guardian And Warda Case : Guardian And Warda Case	JHLT04
68	Injunction O-39 : Injunction O-39	JHLT04
47	Insolvancy : Insolvancy	JHLT04
73	Interpleader Suit : Interpleader Suit	JHLT04
98	Land Acquisition Case : Land Acquisition Case	JHLT04
2	Land Ref : Land Ref	JHLT04
40	Lunacy : Lunacy	JHLT04
87	Mesne Profit : Mesne Profit	JHLT04
33	Misc Cases O-9 : Misc Cases O-9	JHLT04
99	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHLT04
25	Miscellanous : Miscellanous	JHLT04
96	ORIGINAL SUIT : ORIGINAL SUIT	JHLT04
84	Partition Suits : Partition Suits	JHLT04
58	Pauper Application : Pauper Application	JHLT04
89	Reg Civil Suit : Reg Civil Suit	JHLT04
35	Regular Petition : Regular Petition	JHLT04
27	Rent Suit : Rent Suit	JHLT04
28	Restoration of Suits o-9 r-4 : Restoration of Suits o-9 r-4	JHLT04
4	Setting aside exparte Order : Setting aside exparte Order	JHLT04
77	Small Cause Suit : Small Cause Suit	JHLT04
91	Spl Civ Suit : Spl Civ Suit	JHLT04
29	Spl Darkhast : Spl Darkhast	JHLT04
13	Succession : SUCCESSION CASE	JHLT04
65	Suit Bny Person of Unsoiund MI : Suit Bny Person of Unsoiund MI	JHLT04
17	Suit by Corroration/Firm/Trus : Suit by Corroration/Firm/Trus	JHLT04
72	Suit by minor : Suit by minor	JHLT04
16	Suite by Govt of Public office : Suite by Govt of Public office	JHLT04
36	Suit for special Perpormance O : Suit for special Perpormance O	JHLT04
15	Suit of Publiction Nausiance : Suit of Publiction Nausianc	JHLT04
51	Summary Case : Summary Case	JHLT04
53	Summary Suit : Summary Suit	JHLT04
74	Title Arbitration Suit : Title Arbitration Suit	JHLT04
6	Title Mortgage Suit : Title Mortgage Suit	JHLT04
83	Title Suits : Title Suits	JHLT04
50	Administrative Suit : Administrative Suit	JHLT02
4	App. OF res Subjudice U/S 10 : App. OF res Subjudice U/S 10	JHLT02
39	Arb : ARBITRATION	JHLT02
20	ARBITRATION R.D : ARBITRATION R.D	JHLT02
61	Civil Misc. Case : Civil  Misc. Case	JHLT02
102	Commercial Suit : Commercial Suit	JHLT02
21	Contempt Proceedings : Contempt Proceedings	JHLT02
73	Cross Suit : Cross Suit	JHLT02
58	Dissolution of Patnership : Dissolution of Patnership	JHLT02
15	ECO Off : ECO Off	JHLT02
89	ELECt Petn : ELECT Petn	JHLT02
86	Eviction Suits : Eviction Suits	JHLT02
38	Execution Case : Execution Case	JHLT02
17	Injunction O 39 : Injunction O 39	JHLT02
47	Insolvancy : Insolvancy	JHLT02
31	Interpleader Suit : Interpleader Suit	JHLT02
98	Land Acquisition Case : Land Acquisition Case	JHLT02
99	Land Acquisition Execution Case : Land Acquisition Execution Cas	JHLT02
100	Land Acquisition Misc. Case : Land Acquisition Misc. Case	JHLT02
35	Mesne Profit : Mesne Profit	JHLT02
101	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHLT02
33	Miscellanesous : Miscellanesous	JHLT02
34	Misc Non Judicial Cases : Misc Non Judicial Cases	JHLT02
32	Misc Petition : Misc Petition	JHLT02
85	Money Suits : Money Suits	JHLT02
96	ORIGINAL SUIT : ORIGINAL SUIT	JHLT02
84	Partition Suits : Partition Suits	JHLT02
36	Pauper Application : Pauper Application	JHLT02
91	REG CIVIL Suit : REG CIVIL Suit	JHLT02
67	Rent Suit : Rent Suit	JHLT02
3	Restoration of Suits 09 R 4 : Restoration of Suits 09 R 4	JHLT02
65	Setting Aside Exparte Order : Setting Aside Exparte Order	JHLT02
29	SPL.CIV Suit : SPL.CIV Suit	JHLT02
16	Suit bny Person of Unsound MI : Suit bny Person of Unsound MI	JHLT02
64	Suit by Corporation Firm Trus : Suit by Corporation Firm Trus	JHLT02
5	Suit by Minor : Suit by Minor	JHLT02
77	Suite By Govt or Public  Offic : Suite By Govt or Public  Offic	JHLT02
25	Suit For Perpertual Injection : Suit For Perpertual Injection	JHLT02
60	Suit of Public Nausiance : Suit of Public Nausiance	JHLT02
27	Summary Suit : Summary Suit	JHLT02
51	Title Arbitration Suit : Title Arbitration Suit	JHLT02
26	Title Mortgege Suit : Title Mortgege Suit	JHLT02
83	Title Suits : Title Suits	JHLT02
68	Title (Ttrade Mark) Suit : Title (Ttrade Mark) Suit	JHLT02
74	T. S. : Title Suit	JHLT02
105	AIR/Motor Acc.C.Case : AIR/Motor Accident Claim Case	JHLT01
89	Anticipatory Bail Petition : Anticipatory Bail Petition	JHLT01
39	Arb : ARBITRATION	JHLT01
91	Bail Petition. : Bail Petition.	JHLT01
60	Children Case : Children Case	JHLT01
35	CIVIL APPEAL : CIVIL APPEAL	JHLT01
75	Civil  Appeals : Civil  Appeals	JHLT01
77	Civil  Misc. Appeal : Civil  Misc. Appeal	JHLT01
76	Civil Misc Appeal RA : Civil Misc Appeal R.A	JHLT01
116	Civil Misc. Case : Civil Misc. Case	JHLT01
117	Civil Revision : Civil Revision	JHLT01
138	Commercial Appeal : Commercial Appeal	JHLT01
146	Commercial  Suit : Commercial Suit	JHLT01
58	Comp : Complaint Cases	JHLT01
107	Complain POCSO Case : Complain POCSO Case	JHLT01
20	CRIMINAL APPEAL : CRIMINAL APPEAL	JHLT01
36	CRIMINAL MISC. : CRIMINAL MISC.	JHLT01
21	CRIMINAL REVISION : CRIMINAL REVISION	JHLT01
132	Drug Cosmetic Case : Drug Cosmetic Case	JHLT01
121	Electricity Act Case : Electricity Act Case	JHLT01
142	Electricity Act Case E : Electricity Act Case E	JHLT01
133	Electricity Act Case Splt A : Electricity Act Case Splt A	JHLT01
134	Electricity Act Case Splt B : Electricity Act Case Splt B	JHLT01
135	Electricity Act Case Splt C : Electricity Act Case Splt C	JHLT01
137	Electricity Act Case Splt D : Electricity Act Case Splt D	JHLT01
38	Exec. Cases : Execution Cases	JHLT01
115	Execution Case : Execution Cas	JHLT01
100	Final Decree : Final Decree	JHLT01
18	Gaurdianship Case : Gaurdianship Case	JHLT01
65	G.R. CASES : G.R. CASES	JHLT01
47	Insolvancy : Insolvancy	JHLT01
111	Juvenile Case : Juvenile Case	JHLT01
119	L Acq Case : L Acq Case	JHLT01
120	Land Acquisition Appeal : Land Acquisition Appeal	JHLT01
62	L.A Ref : L.A Ref	JHLT01
82	Letter Of Admin. Cases : Letter of Administration Cases	JHLT01
112	MACT Execution Case : M.A.C.T. Execution Case	JHLT01
69	Misc Case- 125 Cr.p.c : Misc. Case U/S- 125 Cr. P.C	JHLT01
106	Misc. Case-125 Cr.PC : Misc. Case-125 Cr.PC	JHLT01
128	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHLT01
127	MISC. CRI. APPLICATION : MISC. CRI. APPLICATION	JHLT01
2	Misc RC : Misc RC	JHLT01
79	Motor Accident Claims Case : Motor Accident Claims Case	JHLT01
136	NCB Crime Case : NCB Crime Case	JHLT01
52	N.D.P.S Case : N.D.P.S Case	JHLT01
141	N.D.P.S Case Splt. D : N.D.P.S Case Splt. D	JHLT01
104	N.D.P.S splt C : N.D.P.S C	JHLT01
97	N.D.P.S Splt.Case A : N.D.P.S Splt.Case A	JHLT01
98	N.D.P.S Splt.Case B : N.D.P.S Splt.Case B	JHLT01
113	Original Suit : Orininal Suit Probate	JHLT01
122	POTA Case : POTA Case	JHLT01
81	Probate Cases : Probate Cases	JHLT01
61	Revocation Case : Revocation Case	JHLT01
64	SC/ST Case : SC/ST Case	JHLT01
143	SC/ST Case A : SC/ST Case A	JHLT01
144	SC/ST Case B : SC/ST Case B	JHLT01
124	SC/ST Complaint Case : SC/ST Complaint Case	JHLT01
125	SC/ST Split up Case : SC/ST Split up Case	JHLT01
57	SESSION TRIAL : SESSION TRIAL	JHLT01
108	Special POCSO Case : Special POCSO Case	JHLT01
139	Special Pocso Case Supl. A : Special Pocso Case Supl. A	JHLT01
140	Special Pocso Case Supl. B : Special Pocso Case Supl. B	JHLT01
126	Special Pocso Supl. Case : Special Pocso Supl. Case	JHLT01
145	Special Pocso Supl. Case  C : Special Pocso Supl. Case C	JHLT01
56	SPl NIA : SPl NIA	JHLT01
96	S.T. Sp.Case : Sessions Trial Spilt  up Case	JHLT01
101	St.spl case A : ST split case A	JHLT01
102	St Spl case C : ST splitup case C	JHLT01
109	S.T Split Case D : S.T Split Case D	JHLT01
99	S.T.Splt.Case B : S.T Case	JHLT01
13	Succession Certificate Case : Succession Certificate Case	JHLT01
67	Sum. Civil Suit : Sum. Civil Suit	JHLT01
131	Title Eviction Suit : Title Eviction Suit	JHLT01
130	Title Mortgage Suit : Title Mortgage Suit	JHLT01
114	Transfer Petition : Transfer Petition	JHLT01
83	Trust Appeal : Trust Appeal	JHLT01
66	Trust Suit : Trust Suit	JHLT01
59	TS PS/Mor/DEC/ARB : TS PS/Mor/DEC/ARB	JHLT01
129	Weight And Measurement Act : Weight And Measurement Act	JHLT01
61	Civil Misc. Case : Civil  Misc. Case	JHLT05
38	Execution Case : Execution Case	JHLT05
98	Gaurdianship Suit : Gaurdianship Suit	JHLT05
17	Maint Alteration Case : Maint Alteration Case	JHLT05
80	Matrimonial Suit : Matrimonial Suit	JHLT05
69	Misc Case- 125 Cr.p.c : Misc. Case U/S- 125 Cr. P.C	JHLT05
102	Misc. Civil Application : Misc. Civil Application	JHLT05
103	Misc. Criminal Application : Misc. Criminal Application	JHLT05
95	Original Maint Case : Original Mainte Case	JHLT05
99	Original Maint.,Case : Original Maint Case	JHLT05
96	Original Maintenance Case : Original Maintenace  Case	JHLT05
100	Original Suit : Original Suit	JHLT05
174	Central Exice : Central Exice	JHGW03
171	C.f : Complaint by Forest	JHGW03
184	c.f A : c.f A	JHGW03
172	C.g : Complaint by Government	JHGW03
185	Complain Sup. A : Complain Sup. A	JHGW03
170	Complaint Case : Cr. Complain Case	JHGW03
159	Cr. Case Complaint (O) : Cr. Case Complaint (O)	JHGW03
142	Cr. Case Complaint (P) : Cr. Case Complaint (P)	JHGW03
157	Cr.l Misc. Case : Criminal Mislaneous	JHGW03
149	Excise Act : Excise Act	JHGW03
169	G.R. Cases : General Register	JHGW03
180	G.R.- Supl.- A : G.R.	JHGW03
181	G.R.- Supl.- B : G.R.	JHGW03
182	G.R.-Supl.-C : G.R.-Supl.-C	JHGW03
183	G.R.-Supl.-D : G.R.-Supl.-D	JHGW03
178	Informatory Petition : Informatory Petition	JHGW03
187	MISC. CRI. APPLICATION : MISC. CRI. APPLICATION	JHGW03
105	Civil Misc Case : MISC CASES	JHGW04
112	EXECUITION CASES : EXECUITION CASES	JHGW04
146	L.A : L.A	JHGW04
166	Misc. Case : Misc. Case	JHGW04
2	MISC. CIVIL APPEAL : MISC. CIVIL APPEAL	JHGW04
179	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHGW04
158	Mislaneous : Mislaneous	JHGW04
103	M S : MONEY SUIT	JHGW04
102	Original Suit : Original Suit(jr)	JHGW04
164	P.a : Partion Appeal	JHGW04
178	Partition Suit : Partition Suit	JHGW04
165	Sucession : Sucession case	JHGW04
1	TITLE APPEAL : TITLE APPEAL	JHGW04
143	Title Suit : Title Suit	JHGW04
105	Civil Misc. Case : MISC CASES	JHGW02
3	CIVIL SUIT : CIVIL SUIT	JHGW02
104	EVICTION SUIT : EVICTION SUIT(U/S BBC ACT/ U/S	JHGW02
112	EXECUITION CASES : EXECUITION CASES	JHGW02
146	L.A : L.A	JHGW02
166	Misc. Case : Misc. Case	JHGW02
2	MISC. CIVIL APPEAL : MISC. CIVIL APPEAL	JHGW02
179	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHGW02
158	Mislaneous : Mislaneous	JHGW02
103	MONEY SUIT : MONEY SUIT	JHGW02
143	Original Suit : Title Suit	JHGW02
164	P.a : Partion Appeal	JHGW02
102	P S / T S : PARTION SUIT / TITLE Suit	JHGW02
177	Succession : Succession	JHGW02
145	SUCCESSION : SUCCESSION	JHGW02
1	TITLE APPEAL : TITLE APPEAL	JHGW02
155	Title Eviction Suit : Title Eviction Suit	JHGW02
154	Title Mortgage Suit : Title Mortgage Suit	JHGW02
166	Civil Misc. Case : Civil Misc. Case	JHGW05
157	Criminal Mislaneous : Criminal Mislaneous	JHGW05
180	EXEC. CASE : EXEC. CASE	JHGW05
112	EXECUITION CASES : EXECUITION CASES	JHGW05
161	GUARDIANSHIP : GUARDIANSHIP	JHGW05
183	Maint Alter Case : Maint Alter Case	JHGW05
158	Mislaneous : Mislaneous	JHGW05
107	Original Suit : MATRIMONIAL CASE	JHGW05
156	Orig. Mantenance Cas : Maintainance	JHGW05
179	Anticipatory Bail Pt : Anticipatory Bail Petition	JHGW01
178	Bail Petition : Bail Petition	JHGW01
171	C.f : Complaint by Forest	JHGW01
172	C.g : Complaint by Government	JHGW01
196	Children Cases : Children Cases	JHGW01
1	Civil Appeal : Civil Appeal	JHGW01
2	CIVIL MISC. APPEAL : CIVIL MISC.  APPEAL	JHGW01
3	CIVIL SUIT : CIVIL SUIT	JHGW01
189	Complaint Case : criminal	JHGW01
207	Complaint CaseA : Complaint CaseA	JHGW01
208	Complaint CaseB : Complaint CaseB	JHGW01
209	Complaint CaseC : Complaint CaseC	JHGW01
159	Cr. Case Complaint (O) : Cr. Case Complaint (O)	JHGW01
170	Cr. Complain Case : Cr. Complain Case	JHGW01
12	CRI. APPEAL : CRI. APPEAL	JHGW01
21	CRI. CASE : CRI. CASE	JHGW01
14	CRI. M.A. : CRI. M.A.	JHGW01
30	Criminal Misc. : Criminal Misc.	JHGW01
157	Criminal Mislaneous : Criminal Mislaneous	JHGW01
162	Criminal Revision : Criminal Revision	JHGW01
147	Domestic Violence Act 2005 : Domestic Violence Act 2005	JHGW01
197	Drug Cosmetic : Drug Cosmetic	JHGW01
188	Electricity Case : Electricity Case	JHGW01
192	Electricity Case(A) : Electricity Case(A)	JHGW01
193	Electricity Case(B) : Electricity Case(B)	JHGW01
203	Electricity Case(C) : Electricity Case(C)	JHGW01
198	Electricity Case(D) : Electricity Case(D)	JHGW01
199	Electricity Case(E) : Electricity Case(E)	JHGW01
200	Electricity Case(F) : Electricity Case(F)	JHGW01
201	Electricity Case(G) : Electricity Case(G)	JHGW01
202	Electricity Case(H) : Electricity Case(H)	JHGW01
167	Eviction Appeal : Eviction Appeal	JHGW01
104	EVICTION SUIT(U/S BBC ACT/ U/S 111 TP ACT) : EVICTION SUIT(U/S BBC ACT/ U/S	JHGW01
149	Excise Act : Excise Act	JHGW01
112	EXECUITION CASES : EXECUITION CASES	JHGW01
169	G. R. Cases : General Register	JHGW01
146	L.A : L.A	JHGW01
109	LAND ACQUISTAION CASES : LAND ACQUISTAION CASES	JHGW01
105	MISC CASES a) O-9 , R-4  b) O-9, R-9 c) U/S 47CPC : MISC CASES a) O-9 , R-4  b) O-	JHGW01
191	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHGW01
190	MISC CRI. APPLICATION : mis	JHGW01
158	Mislaneous : Mislaneous	JHGW01
163	Motor Acc Claim Case : Motor accident case	JHGW01
26	NDPS M.A. : NDPS M.A.	JHGW01
15	NDPS. S. CASE : NDPS. S. CASE	JHGW01
143	Original Suit : Title Suit	JHGW01
164	P.a : Partion Appeal	JHGW01
102	PARTION SUIT / TITLE PARTION SUIT : PARTION SUIT / TITLE PARTION S	JHGW01
180	Partition Appeal : Partition Appeal	JHGW01
185	POCSO Case : POCSO Case	JHGW01
204	POCSO CaseA : POCSO CaseA	JHGW01
205	POCSO CaseB : POCSO CaseB	JHGW01
206	POCSO CaseC : POCSO CaseC	JHGW01
108	PROBATE CASE : PROBATE CASE	JHGW01
166	Revocation Case : Revocation Case	JHGW01
194	Schedule Caste. Schedule Trail- A : Schedule Case. Schedule Trail- A	JHGW01
195	Schedule Caste. Schedule Trail- B : Schedule Case. Schedule Trail- B	JHGW01
168	Schedule Caste. Schedule Tribe : Schedule Case. Schedule Tribe	JHGW01
10	Sessions Trial : SESSION TRIAL	JHGW01
182	Session Trial Supl A : Session Trial Supl A	JHGW01
183	Session Trial Supl B : Session Trial Supl B	JHGW01
184	Session Trial Supl C : Session Trial Supl C	JHGW01
181	ST suplt. : supplimentary record	JHGW01
165	Sucession : Sucession case	JHGW01
111	SUCESSION CERTIFICATE CASES : SUCESSION CERTIFICATE CASES	JHGW01
155	Title Eviction Suit : Title Eviction Suit	JHGW01
154	Title Mortgage Suit : Title Mortgage Suit	JHGW01
186	Transfer Petition : Transfer Petition	JHGW01
96	Civil Misc : Civil Misc	JHSG02
113	Commercial Suit : Commercial Suit	JHSG02
111	Execution case : Execution case	JHSG02
112	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHSG02
70	Original Suit : Original Suit	JHSG02
113	ARBITRATION CASE : ARBITRATION CASE	JHSG07
109	Civil Misc : Civil misc	JHSG07
96	Civil Misc CASE : Civil Misc CASE	JHSG07
111	Execution case : Execution case	JHSG07
112	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHSG07
70	Original Suit : Original Suit	JHSG07
3	Anticipatory Bail Pe : Anticipatory Bail Petition	JHSG06
54	Bail Petition : Bail  Petition	JHSG06
66	Civil Appeal : Civil Appeal	JHSG06
110	Civil Misc Appeal : Civil misc Appeal	JHSG06
115	Commercial Appeals : Commercial Appeals	JHSG06
114	Commercial Suits : Commercial Suits	JHSG06
90	Complaint cases : Complaint cases	JHSG06
82	Criminal Misc Petition : Criminal misc petition	JHSG06
20	Crl.Appeal : CRIMINAL APPEAL	JHSG06
21	Crl.Revision : Criminal Revision	JHSG06
50	Electricity Act Case : Electricity Act Case	JHSG06
63	G.R.Cases : G.R.Cases	JHSG06
47	Insolvency case : Insolvancy case	JHSG06
2	Land Acquisition App : Land Acquisition App	JHSG06
107	Letter of Administra : Letter of Administra	JHSG06
112	MISC CIVIL APPLICATION : MISC CIVIL APPLICATION	JHSG06
111	MISC CRL APPLICATION : MISC CRL APPLICATION	JHSG06
103	Motor Accident Claim : Motor Accident Claim	JHSG06
52	NDPS cases : NDPS Act Cases	JHSG06
113	POCSO : POCSO	JHSG06
7	PROBATE : PROBATE	JHSG06
109	Revocation case : Revocation case	JHSG06
102	Rps : RPS	JHSG06
79	SC/ST CASE : SC/ST case	JHSG06
57	Sessions Trial : Sessions Cases	JHSG06
78	Succesion Certificate : Succesion Certificate	JHSG06
76	Transfer Petition : Transfer Petition	JHSG06
86	Civil Misc. cases : Civil Misc. cases	JHSG09
61	Maintenance Alteration case : Maintenance Alteration case	JHSG09
84	Original Maintenance : Original Maintenance case	JHSG09
85	Original Suit : Original Suit	JHSG09
86	Civil Misc. cases : Civil Misc. cases	JHSG05
14	CRI. M.A. : CRI. M.A.	JHHB03
61	Maintenance Alteration case : Maintenance Alteration case	JHSG05
84	Original Maintenance : Original Maintenance case	JHSG05
85	Original Suit : Original Suit	JHSG05
90	Complaint case : Complaint case	JHSG08
63	GR : General Register No	JHSG08
112	IPC : IPC	JHSG08
36	Misc Criminal  Application : Misc Criminal Application	JHSG08
111	Misc Criminal Case : Misc Criminal Case	JHSG08
59	Police Act Cases : Police Challan	JHSG08
104	Railway Act Case : Railway Act Case	JHSG08
8	Regular C B I Caes : Regular C B I Caes	JHSG08
90	Complaint case : Complaint case	JHSG03
36	Crl Misc Cases : Criminal Misc Cases	JHSG03
63	GR : General Register No	JHSG03
112	JUVENILE CASE : JUVENILE CASE	JHSG03
111	Misc Criminal Application : Misc Criminal Application	JHSG03
59	Police Act Cases : Police Act Cases	JHSG03
104	Railway Act Case : Railway Act Case	JHSG03
8	Regular C B I Caes : Regular C B I Caes	JHSG03
3	Anticipatory Bail Pe : Anticipatory Bail Petition	JHSG01
54	Bail Petition : Bail  Petition	JHSG01
116	Children Case : Children Case	JHSG01
66	Civil Appeal : Civil Appeal	JHSG01
96	Civil Misc : Civil Misc	JHSG01
110	Civil Misc Appeal : Civil misc Appeal	JHSG01
115	Commercial Appeals : Commercial Appeals	JHSG01
114	Commercial Suits : Commercial Suits	JHSG01
90	Complaint cases : Complaint cases	JHSG01
82	Criminal Misc Petition : Criminal misc petition	JHSG01
20	Crl.Appeal : CRIMINAL APPEAL	JHSG01
21	Crl.Revision : Criminal Revision	JHSG01
117	Drug and Cosmetics Case : Drug and Cosmetics Case	JHSG01
50	Electricity Act Case : Electricity Act Case	JHSG01
63	G.R.Cases : G.R.Cases	JHSG01
47	Insolvency case : Insolvancy case	JHSG01
2	Land Acquisition App : Land Acquisition App	JHSG01
107	Letter of Administra : Letter of Administra	JHSG01
112	MISC CIVIL APPLICATION : MISC CIVIL APPLICATION	JHSG01
111	MISC CRL APPLICATION : MISC CRL APPLICATION	JHSG01
103	Motor Accident Claim : Motor Accident Claim	JHSG01
52	NDPS cases : NDPS Act Cases	JHSG01
118	ORIGINAL SUIT : ORIGINAL SUIT	JHSG01
113	POCSO : POCSO	JHSG01
7	PROBATE : PROBATE	JHSG01
109	Revocation case : Revocation case	JHSG01
102	Rps : RPS	JHSG01
79	SC/ST CASE : SC/ST case	JHSG01
57	Sessions Trial : Sessions Cases	JHSG01
78	Succesion Certificate : Succesion Certificate	JHSG01
76	Transfer Petition : Transfer Petition	JHSG01
89	ADMINISTRATIVE SUITE : ADMINISTRATIVE SUITE	JHRN02
138	APP. OF RES. SUBJUDICE U/S 10 : APP. OF RES. SUBJUDICE U/S 10	JHRN02
23	ARBITRATION CASE : ARBITRATION CASE	JHRN02
47	ARBITRATION R.D. : ARBITRATION R.D.	JHRN02
158	Civil Misc Case : Civil Misc Case	JHRN02
2	CivMiscAppeal : CivMiscAppeal	JHRN02
212	Commercial (Arbitration) : Commercial (Arbitration)	JHRN02
213	Commercial Revocation : CommRevoc	JHRN02
214	Comm. Exec : Commercial Execution	JHRN02
215	Comm. (Suit) Case : Commercial (Suit) Case	JHRN02
48	CONTEMPT PROCEEDINGS : CONTEMPT PROCEEDINGS	JHRN02
98	CROSS SUIT : CROSS SUIT	JHRN02
100	DECLARATIVCE SUIT : DECLARATIVCE SUIT	JHRN02
90	DISSOLUTION OF PATNERSHIP : DISSOLUTION OF PATNERSHIP	JHRN02
207	EcoOff. Case : ECO. Off.	JHRN02
8	ELECT. PETN : ELECT. PETN	JHRN02
104	EVICTION SUIT(U/S BBC ACT/ U/S 111 TP ACT) : EVICTION SUIT(U/S BBC ACT/ U/S	JHRN02
112	Execution : Execution	JHRN02
33	FINAL DECREE : FINAL DECREE	JHRN02
135	INJUNCTION O-39 , R1, 2 : INJUNCTION O-39 , R1, 2	JHRN02
35	INSOLVENCY : INSOLVENCY	JHRN02
97	INTERPLEADER SUIT : INTERPLEADER SUIT	JHRN02
146	LAC : Land Acquisition Case	JHRN02
157	L.Acq Misc : Land Acqusition Misc Case	JHRN02
5	LAEC : Land Acquisition ExecutionCase	JHRN02
109	LAND ACQUISTAION CASES : LAND ACQUISTAION CASES	JHRN02
110	LUNACY/ ADOPTION/ INSOLVANCY : LUNACY/ ADOPTION/ INSOLVANCY	JHRN02
38	MESNE PROFIT : MESNE PROFIT	JHRN02
105	MISC CASES a) O-9 , R-4  b) O-9, R-9 c) U/S 47CPC : MISC CASES a) O-9 , R-4  b) O-	JHRN02
210	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHRN02
211	MISC. CRI. APPLICATION : MISC. CRI. APPLICATION	JHRN02
208	Original Suit : Original Suit	JHRN02
102	PARTION SUIT / TITLE PARTION SUIT : PARTION SUIT / TITLE PARTION S	JHRN02
45	PAUPER APPLICATION : PAUPER APPLICATION	JHRN02
17	REG. CIVIL SUIT : REG. CIVIL SUIT	JHRN02
42	RENT SUIT : RENT SUIT	JHRN02
120	RESTORATION OF SUITS 0-9 R-4 , 0-9 , R -9 : RESTORATION OF SUITS 0-9 R-4 ,	JHRN02
121	SETTING  ASIDE EXPARTE ORDER 0-9 - R -13 : SETTING  ASIDE EXPARTE ORDER 0	JHRN02
41	SMALL CAUSE SUIT : SMALL CAUSE SUIT	JHRN02
19	SPL.CIV. SUIT : SPL.CIV. SUIT	JHRN02
39	SUCCESSION : SUCCESSION	JHRN02
94	SUIT BNY PERSON OF UNSOUND MIND : SUIT BNY PERSON OF UNSOUND MI	JHRN02
92	SUIT BY  CORPORATION/FIRM/TRUSTEE/INDEGENT PERSON : SUIT BY  CORPORATION/FIRM/TRUS	JHRN02
93	SUIT BY MINOR : SUIT BY MINOR	JHRN02
22	Insolvency Case : Insolvency Case	JHRG01
91	SUITE BY GOVT OR PUBLIC  OFFICERS IN OFFICIAL CAPA : SUITE BY GOVT OR PUBLIC  OFFIC	JHRN02
101	SUIT FOR PERPERTUAL INJECTION : SUIT FOR PERPERTUAL INJECTION	JHRN02
99	SUIT FOR SPECIAL PERFORMANCE OF CONTRACT : SUIT FOR SPECIAL PERFORMANCE O	JHRN02
96	SUIT OF PUBLIC NAUSIANCE : SUIT OF PUBLIC NAUSIANCE	JHRN02
95	SUMMARY SUIT : SUMMARY SUIT	JHRN02
143	Title Suit : Title Suit	JHRN02
170	zCivil Misc. Case : zcivil misc. case	JHRN02
138	APP. OF RES. SUBJUDICE U/S 10 : APP. OF RES. SUBJUDICE U/S 10	JHRN04
23	ARBITRATION CASE : ARBITRATION CASE	JHRN04
47	ARBITRATION R.D. : ARBITRATION R.D.	JHRN04
158	Civil  Misc Case : Civil  Misc Case	JHRN04
48	CONTEMPT PROCEEDINGS : CONTEMPT PROCEEDINGS	JHRN04
98	CROSS SUIT : CROSS SUIT	JHRN04
100	DECLARATIVCE SUIT : DECLARATIVCE SUIT	JHRN04
90	DISSOLUTION OF PATNERSHIP : DISSOLUTION OF PATNERSHIP	JHRN04
8	ELECT. PETN : ELECT. PETN	JHRN04
104	EVICTION SUIT(U/S BBC ACT/ U/S 111 TP ACT) : EVICTION SUIT(U/S BBC ACT/ U/S	JHRN04
112	Execution : Execution	JHRN04
33	FINAL DECREE : FINAL DECREE	JHRN04
37	GUARDIAN and  WARDS CASE : GUARDIAN and WARDS CASE	JHRN04
135	INJUNCTION O-39 , R1, 2 : INJUNCTION O-39 , R1, 2	JHRN04
35	INSOLVENCY : INSOLVENCY	JHRN04
97	INTERPLEADER SUIT : INTERPLEADER SUIT	JHRN04
109	LAND ACQUISTAION CASES : LAND ACQUISTAION CASES	JHRN04
5	LAND REF. : LAND REF.	JHRN04
110	LUNACY/ ADOPTION/ INSOLVANCY : LUNACY/ ADOPTION/ INSOLVANCY	JHRN04
38	MESNE PROFIT : MESNE PROFIT	JHRN04
105	MISC CASES a) O-9 , R-4  b) O-9, R-9 c) U/S 47CPC : MISC CASES a) O-9 , R-4  b) O-	JHRN04
209	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHRN04
210	MISC. CRIMINAL APPLICATION : MISC. CRIMINAL APPLICATION	JHRN04
208	OriginalSuit : OriginalSuit	JHRN04
102	PARTION SUIT / TITLE PARTION SUIT : PARTION SUIT / TITLE PARTION S	JHRN04
45	PAUPER APPLICATION : PAUPER APPLICATION	JHRN04
17	REG. CIVIL SUIT : REG. CIVIL SUIT	JHRN04
28	REGULAR PETITION : REGULAR PETITION	JHRN04
42	RENT SUIT : RENT SUIT	JHRN04
120	RESTORATION OF SUITS 0-9 R-4 , 0-9 , R -9 : RESTORATION OF SUITS 0-9 R-4 ,	JHRN04
121	SETTING  ASIDE EXPARTE ORDER 0-9 - R -13 : SETTING  ASIDE EXPARTE ORDER 0	JHRN04
41	SMALL CAUSE SUIT : SMALL CAUSE SUIT	JHRN04
19	SPL.CIV. SUIT : SPL.CIV. SUIT	JHRN04
29	SPL. DARKHAST : SPL. DARKHAST	JHRN04
94	SUIT BNY PERSON OF UNSOIUND MIND : SUIT BNY PERSON OF UNSOIUND MI	JHRN04
92	SUIT BY  CORPORATION/FIRM/TRUSTEE/INDEGENT PERSON : SUIT BY  CORPORATION/FIRM/TRUS	JHRN04
93	SUIT BY MINOR : SUIT BY MINOR	JHRN04
91	SUITE BY GOVT OR PUBLIC  OFFICERS IN OFFICIAL CAPA : SUITE BY GOVT OR PUBLIC  OFFIC	JHRN04
101	SUIT FOR PERPERTUAL INJECTION : SUIT FOR PERPERTUAL INJECTION	JHRN04
96	SUIT OF PUBLIC NAUSIANCE : SUIT OF PUBLIC NAUSIANCE	JHRN04
22	SUMMARY CASE : SUMMARY CASE	JHRN04
95	SUMMARY SUIT : SUMMARY SUIT	JHRN04
215	ABP : Anticipatory Bail Application	JHRN03
165	B.P : BAIL PETITION	JHRN03
173	Complaint Case : Complaint Case	JHRN03
159	Cr. Case Complaint (O) : Cr. Case Complaint (O)	JHRN03
142	Cr. Case Complaint (P) : Cr. Case Complaint (P)	JHRN03
168	Cr.ex.case : Cr.Execution Case	JHRN03
9	Cri .APPLN. : C.APPLN.	JHRN03
24	CRI. BAIL APPLN. : CRI. BAIL APPLN.	JHRN03
21	CRI. CASE : CRI. CASE	JHRN03
157	Cri. Misc Case : Criminal Misc Case	JHRN03
13	Cri. Rev : CRI. REVISION	JHRN03
80	DISTRESS WARRENT : DISTRESS WARRENT	JHRN03
213	EC Cases : EC Cases	JHRN03
32	E.S.I. ACT  CASE : E.S.I. ACT  CASE	JHRN03
164	GR : GR	JHRN03
176	GRPreCog : GRPreCog	JHRN03
38	MESNE PROFIT : MESNE PROFIT	JHRN03
214	MISC. CRI. APPLICATION : MISC. CRIMINAL APPLICATION	JHRN03
184	Misc Petition : Misc Petition	JHRN03
158	Mislaneous : Mislaneous	JHRN03
198	MP MLA : MP MLA CASE	JHRN03
171	Ms. Petition : misc. petition	JHRN03
30	OTHER MISC. CRI. APPLN. : OTHER MISC. CRI. APPLN.	JHRN03
181	Rail Case : Rail Case	JHRN03
20	REG. CRI. CASE : REG. CRI. CASE	JHRN03
153	Regular Bail : Regular Bail	JHRN03
212	Regular CBI : RC Case	JHRN03
31	REVIEW APPLICATION : REVIEW APPLICATION	JHRN03
25	SPL. CRI. MA : SPL. CRI. MA	JHRN03
162	Spl(e)case : Spl(Elect.)Case	JHRN03
148	Weight and Measurement Act : Weight and Measurement Act	JHRN03
209	Civ Misc : Civ Misc	JHRN05
112	Exec. : Exec.	JHRN05
37	Guardianship : Guardianship	JHRN05
211	MAC : Maintanence Alteration Case	JHRN05
212	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHRN05
213	MISC. CRI APPLICATION : MISC. CRI APPLICATION	JHRN05
208	Original Maint Case : Original Maint Case	JHRN05
107	OriginalSuit : OriginalSuit	JHRN05
152	ABP : ABP	JHRN01
226	ATS COURT CASE : ATS COURT CASE	JHRN01
165	B.P : BailPetition	JHRN01
218	CBC : Coal Bearing Cases	JHRN01
225	CBC Exe : Coal Bearing Execution Case	JHRN01
22	Children Case : Children Case	JHRN01
227	CID COURT CASE : CID COURT CASE	JHRN01
1	Civil Appeal : Civil Appeal	JHRN01
170	Civil Misc. Case : civil misc. case	JHRN01
215	CIVIL REVISION : CIVIL REVISION	JHRN01
2	CivMiscAppl : Civil Misc Appeal	JHRN01
228	Comm Appeal : Commercial Appeal	JHRN01
23	Commercial (Arbitration) : Commercial(Arbitration)	JHRN01
219	Commercial Execution : Comm. Exec	JHRN01
98	Commercial (Suit) Case : Comm. (Suit) Case	JHRN01
220	CommRevoc : Commercial Revocation	JHRN01
173	Complaint : Complaint Case	JHRN01
12	CrAppeal : CrAppeal	JHRN01
208	Cri Misc : Cri MiscCase	JHRN01
157	Cri.(misc.)case : Criminal(Misc.)CASE	JHRN01
13	CriRev : CriRev	JHRN01
7	CYBER CASE : CYBER CASE	JHRN01
221	DrugCosmetic : DrugCosmetic	JHRN01
120	ECIR Case : ECIR Case	JHRN01
207	Eco.offence : Eco.Offence	JHRN01
8	Electricity Act Case : Electricity Act Case	JHRN01
112	Execution : Execution	JHRN01
224	FERA/FEMA CASE : FERA/FEMA CASE	JHRN01
33	FINAL DECREE : FINAL DECREE	JHRN01
164	GR : GR	JHRN01
161	GRDSHIP. : GUARDIANSHIP	JHRN01
35	INSOLVENCY : INSOLVENCY	JHRN01
109	L Acq Case : L Acq Case	JHRN01
195	L. Admn. : Letter Of Administration	JHRN01
5	LandAcqAppl : LandAcqAppl	JHRN01
146	L.a.ref : L.A.Ref	JHRN01
223	MISC. CIVIL  APPLICATION : MISC. CIVIL APPLICATION	JHRN01
222	MISC. CRI. APPLICATION : Misc Criminal Application	JHRN01
210	MiscMACT : MISCELLANEOUS CASES MACT	JHRN01
121	MiscRC : MiscRC	JHRN01
158	Mislaneous : Mislaneous	JHRN01
209	Motor Accident Claim : Motor Accident Claim	JHRN01
198	MP MLA : MP MLA CASE	JHRN01
166	NDPScase : NDPScase	JHRN01
108	OriginalSuit : OriginalSuit	JHRN01
162	POTACase : POTACase	JHRN01
213	PROBATE CASE : PROBATE CASE	JHRN01
153	Regular Bail : Regular Bail	JHRN01
212	Regular CBI : Regular CBI Case	JHRN01
137	REVIEW/RECALL PET. U/S-114CPC : REVIEW/RECALL PET. U/S-114CPC	JHRN01
214	REVOC : REVOCATION CASE	JHRN01
206	SC_ST : SC_STCase No	JHRN01
10	Session Trial : Session Trial	JHRN01
167	SplNIA : SplNIA	JHRN01
21	SPL . POCSO Case : SPL . POCSO Case	JHRN01
39	SUCC. CERT. : SUCC CERT CASE	JHRN01
81	SUM. CIVIL SUIT : SUM. CIVIL SUIT	JHRN01
175	Title (a)suit : Title Arbitration Suit	JHRN01
155	Title Eviction Suit : Title Eviction Suit	JHRN01
154	Title Mortgage Suit : Title Mortgage Suit	JHRN01
200	T N(tm) S : Title (Ttrade Mark ) Suit	JHRN01
174	T(p)suit : Title(partion)Suit	JHRN01
216	TRANS. PET. : TRANSFER PETITION	JHRN01
43	TRUST APPEAL : TRUST APPEAL	JHRN01
44	TRUST SUIT : TRUST SUIT	JHRN01
143	TS-P/M/D/A : TS-PS/MOR/DEC/ARB	JHRN01
217	VIG. COMPLAINT : VIG. COMPLAINT	JHRN01
193	Vigi. Case : Vigilance Case	JHRN01
4	Arbitration Case : Arbitration Case	JHRG04
3	Civil Misc. Case : Civil Misc. Case	JHRG04
9	Eviction Suit : Eviction Suit	JHRG04
2	Execution Case : Execution Case	JHRG04
10	Final Decree : Final Decree	JHRG04
5	Land Acquisition Case : Land Acquisition Case	JHRG04
7	Misc. Civil Application : Misc. Civil Application	JHRG04
1	Original Suit : Original Suit	JHRG04
8	Title Partition Suit : Title Partition Suit	JHRG04
8	Arbitration Case : Arbitration Case	JHRG02
10	Civil Misc. Case : Civil Misc. Case	JHRG02
12	Commercial Appeal : Commercial Appeal	JHRG02
13	Commercial Execution : Commercial Execution	JHRG02
11	Commercial Suit : Commercial Suit	JHRG02
2	Execution Case : Execution Case	JHRG02
3	Land Acquisition Case : Land Acquisition Case	JHRG02
4	Land Acquisition Execution Case : Land Acquisition Execution Cas	JHRG02
5	Land Acquisition Misc. Case : Land Acquisition Misc. Case	JHRG02
7	Misc. Civil Application : Misc. Civil Application	JHRG02
1	Original Suit : Original Suit	JHRG02
14	Anticipatory Bail Petition : Anticipatory Bail Petition	JHRG01
13	Bail Petition : Bail Petition	JHRG01
8	Children Case : Children Case	JHRG01
17	Civil Appeal : Civil Appeal	JHRG01
18	Civil Misc. Appeal : Civil Misc. Appeal	JHRG01
43	Civil Misc. Case : Civil Misc. Case	JHRG01
25	Civil Transfer Petition : Civil Transfer Petition	JHRG01
58	Commercial Appeal : Commercial Appeal	JHRG01
59	Commercial Execution : Commercial Execution	JHRG01
57	Commercial Suit : Commercial Suit	JHRG01
15	Confiscation Appeal : Confiscation Appeal	JHRG01
10	Criminal Appeal : Criminal Appeal	JHRG01
12	Criminal Misc. : Criminal Misc.	JHRG01
11	Criminal Revision : Criminal Revision	JHRG01
31	Cri. Transfer Petition : Cri. Transfer Petition	JHRG01
52	Drugs and Cosmetics Act Cases : Drugs and Cosmetics Act Cases	JHRG01
4	Electricity Act Cases : Electricity Act Cases	JHRG01
39	Execution Case : Execution Case	JHRG01
23	Guardianship Case : Guardianship Case	JHRG01
27	Land Acquisition Appeal : Land Acquisition Appeal	JHRG01
21	Letter of Administration Case : Letter of Administration Case	JHRG01
7	Misc. Civil Application : Misc. Civil Application	JHRG01
6	Misc.Cri. Application : Misc.Cri. Application	JHRG01
19	Motor Accident Claims Cases : Motor Accident Claims Cases	JHRG01
3	N.D.P.S Case : N.D.P.S Case	JHRG01
26	Original Suit : Original Suit	JHRG01
38	Probate Case : Probate Case	JHRG01
24	Revocation Case : Revocation Case	JHRG01
2	SC and ST Case : SC and ST Case	JHRG01
1	Session Trial : Session Trial	JHRG01
16	Special POCSO Case : Special POCSO Case	JHRG01
54	Split Electricty Act Cases A : Split Electricty Act Cases A	JHRG01
55	Split Electricty Act Cases B : Split Electricty Act Cases B	JHRG01
35	Split N.D.P.S A : Split N.D.P.S A	JHRG01
36	Split N.D.P.S B : Split N.D.P.S B	JHRG01
32	Split Session Trial A : Split Session Trial A	JHRG01
33	Split Session Trial B : Split Session Trial B	JHRG01
34	Split Session Trial C : Split Session Trial C	JHRG01
53	Split Special POCSO Case A : Split Special POCSO Case A	JHRG01
56	Split Special POCSO Case B : Split Special POCSO Case B	JHRG01
20	Succession Certificate Case : Succession Certificate Case	JHRG01
4	Civil Misc. Case : Civil Misc. Case	JHRG05
3	Maintenance Alteration Case : Maintenance Alteration Case	JHRG05
7	Misc. Civil Application : Misc. Civil Application	JHRG05
6	Misc. Cri. Application : Misc. Cri. Application	JHRG05
2	Original Maintenance Case : Original Maintenance Case	JHRG05
1	Original Suit : Original Suit	JHRG05
5	Complaint Case : Complaint Case	JHRG03
12	Crl.Misc.Case : Crl.Misc.Case	JHRG03
3	E.C Cases : E.C Cases	JHRG03
10	Govt. Complaint Case : Govt. Complaint Case	JHRG03
1	G.R Cases : G.R Cases	JHRG03
16	Juvenile Case : Juvenile Case	JHRG03
6	Misc.Cri. Application : Misc.Cri. Application	JHRG03
2	Police Act Cases : Police Act Cases	JHRG03
8	Railway Act Case : Railway Act Case	JHRG03
4	Regular C.B.I Case : Regular C.B.I Case	JHRG03
13	Split G.R Case A : Split G.R Case A	JHRG03
14	Split G.R Case B : Split G.R Case B	JHRG03
15	Split G.R Case C : Split G.R Case C	JHRG03
178	C.m.a : Coal Mines Act	JHKH03
173	Complain Case : Complain Case	JHKH03
228	Complain Case (A) : Complain Case (A)	JHKH03
227	Complain Case (factory) : Complain Case (factory)	JHKH03
226	Complain Case (Food Safety) : Complain Case (Food Safety)	JHKH03
218	Complain Case (forest) : Complain Case (forest)	JHKH03
224	Complain Case forest A : Complain Case forest A	JHKH03
223	Complain Case forest B : Complain Case forest B	JHKH03
225	Complain Case (Labour) : Complain Case (Labour)	JHKH03
217	Complain Case (M.V) : Complain Case (M.V)	JHKH03
229	Complaint (Excise) : Complaint (Excise)	JHKH03
176	Comp.w.f.-complaint wise Forest : Comp.w.f.-complaint wise Forest	JHKH03
157	Cri.(misc.)case : Criminal(Misc.)CASE	JHKH03
147	Domestic Violence Act 2005 : Domestic Violence Act 2005	JHKH03
149	Excise Act : Excise Act	JHKH03
164	General Register No. : General Register No.	JHKH03
209	G.R. Supplementary (A) : G.R. Supplementary (A)	JHKH03
210	G.R Supplementary (B) : G.R Supplementary (B)	JHKH03
211	G.R Supplementary-(C) : G.R Supplementary-(C)	JHKH03
214	G.R. SUPPLEMENTARY (D) : G.R. SUPPLEMENTARY (D)	JHKH03
219	G.R. SUPPLEMENTARY (E) : G.R. SUPPLEMENTARY (E)	JHKH03
221	G.R. SUPPLEMENTARY (F) : G.R. SUPPLEMENTARY (F)	JHKH03
208	MISC. CRI APPLICATION : MISC. CRIMINAL APPLICATION	JHKH03
184	Misc Petition : Misc Petition	JHKH03
15	NDPS Special case : NDPS Speacial Case	JHKH03
215	NDPS SPECIAL CASE A : NDPS SPECIAL CASE A	JHKH03
216	NDPS SPECIAL CASE B : NDPS SPECIAL CASE B	JHKH03
156	Original Maintenance Case : Original Maintenance Case	JHKH03
30	OTHER MISC. CRI. APPLN. : OTHER MISC. CRI. APPLN.	JHKH03
153	Special Pocso : Special Pocso	JHKH03
220	Special Pocso Supplementary-A : Special Pocso Supplementary-A	JHKH03
222	Special Pocso Supplementary-B : Special Pocso Supplementary-B	JHKH03
25	SPL. CRI. MA : SPL. CRI. MA	JHKH03
89	ADMINISTRATIVE SUITE : ADMINISTRATIVE SUITE	JHKH04
138	APP. OF RES. SUBJUDICE U/S 10 : APP. OF RES. SUBJUDICE U/S 10	JHKH04
23	ARBITRATION CASE : ARBITRATION CASE	JHKH04
47	ARBITRATION R.D. : ARBITRATION R.D.	JHKH04
169	Civil Ex Case : civil execution case	JHKH04
170	Civil Misc. Case : civil misc. case	JHKH04
48	CONTEMPT PROCEEDINGS : CONTEMPT PROCEEDINGS	JHKH04
98	CROSS SUIT : CROSS SUIT	JHKH04
100	DECLARATIVCE SUIT : DECLARATIVCE SUIT	JHKH04
90	DISSOLUTION OF PATNERSHIP : DISSOLUTION OF PATNERSHIP	JHKH04
8	ELECT. PETN : ELECT. PETN	JHKH04
104	EVICTION SUIT(U/S BBC ACT/ U/S 111 TP ACT) : EVICTION SUIT(U/S BBC ACT/ U/S	JHKH04
112	EXECUITION CASES : EXECUITION CASES	JHKH04
33	FINAL DECREE : FINAL DECREE	JHKH04
37	GUARDIAN AND WARDS CASE : GUARDIAN AND WARDS CASE	JHKH04
135	INJUNCTION O-39 , R1, 2 : INJUNCTION O-39 , R1, 2	JHKH04
35	INSOLVENCY : INSOLVENCY	JHKH04
97	INTERPLEADER SUIT : INTERPLEADER SUIT	JHKH04
5	Land Acquisition Cases (M) : Land Acquisition Cases (M)	JHKH04
109	LAND ACQUISTAION CASES : LAND ACQUISTAION CASES	JHKH04
110	LUNACY/ ADOPTION/ INSOLVANCY : LUNACY/ ADOPTION/ INSOLVANCY	JHKH04
38	MESNE PROFIT : MESNE PROFIT	JHKH04
105	MISC CASES a) O-9 , R-4  b) O-9, R-9 c) U/S 47CPC : MISC CASES a) O-9 , R-4  b) O-	JHKH04
208	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHKH04
158	Mislaneous : Mislaneous	JHKH04
171	Ms. Petition : misc. petition	JHKH04
156	Original Maintenance : Original Maintenance	JHKH04
143	ORIGINAL SUIT : ORIGINAL SUIT	JHKH04
175	Original (Title/Partition/Money) Suit : Original (Title/Partition/Money) Suit	JHKH04
102	PARTION SUIT / TITLE PARTION SUIT : PARTION SUIT / TITLE PARTION S	JHKH04
45	PAUPER APPLICATION : PAUPER APPLICATION	JHKH04
17	REG. CIVIL SUIT : REG. CIVIL SUIT	JHKH04
28	REGULAR PETITION : REGULAR PETITION	JHKH04
42	RENT SUIT : RENT SUIT	JHKH04
120	RESTORATION OF SUITS 0-9 R-4 , 0-9 , R -9 : RESTORATION OF SUITS 0-9 R-4 ,	JHKH04
121	SETTING  ASIDE EXPARTE ORDER 0-9 - R -13 : SETTING  ASIDE EXPARTE ORDER 0	JHKH04
41	SMALL CAUSE SUIT : SMALL CAUSE SUIT	JHKH04
19	SPL.CIV. SUIT : SPL.CIV. SUIT	JHKH04
29	SPL. DARKHAST : SPL. DARKHAST	JHKH04
39	SUCCESSION PETITION : SUCCESSION PETITION	JHKH04
94	SUIT BNY PERSON OF UNSOIUND MIND : SUIT BNY PERSON OF UNSOIUND MI	JHKH04
92	SUIT BY  CORPORATION/FIRM/TRUSTEE/INDEGENT PERSON : SUIT BY  CORPORATION/FIRM/TRUS	JHKH04
93	SUIT BY MINOR : SUIT BY MINOR	JHKH04
91	SUITE BY GOVT OR PUBLIC  OFFICERS IN OFFICIAL CAPA : SUITE BY GOVT OR PUBLIC  OFFIC	JHKH04
101	SUIT FOR PERPERTUAL INJECTION : SUIT FOR PERPERTUAL INJECTION	JHKH04
99	SUIT FOR SPECIAL PERFORMANCE OF CONTRACT : SUIT FOR SPECIAL PERFORMANCE O	JHKH04
96	SUIT OF PUBLIC NAUSIANCE : SUIT OF PUBLIC NAUSIANCE	JHKH04
22	SUMMARY CASE : SUMMARY CASE	JHKH04
95	SUMMARY SUIT : SUMMARY SUIT	JHKH04
154	Title Mortgage Suit : Title Mortgage Suit	JHKH04
174	TITLE SUIT : TITLE SUIT	JHKH04
89	ADMINISTRATIVE SUITE : ADMINISTRATIVE SUITE	JHKH02
138	APP. OF RES. SUBJUDICE U/S 10 : APP. OF RES. SUBJUDICE U/S 10	JHKH02
23	ARBITRATION CASE : ARBITRATION CASE	JHKH02
47	ARBITRATION R.D. : ARBITRATION R.D.	JHKH02
169	Civil Ex Case : civil execution case	JHKH02
170	Civil Misc. Case : civil misc. case	JHKH02
48	CONTEMPT PROCEEDINGS : CONTEMPT PROCEEDINGS	JHKH02
98	CROSS SUIT : CROSS SUIT	JHKH02
100	DECLARATIVCE SUIT : DECLARATIVCE SUIT	JHKH02
90	DISSOLUTION OF PATNERSHIP : DISSOLUTION OF PATNERSHIP	JHKH02
8	ELECT. PETN : ELECT. PETN	JHKH02
104	EVICTION SUIT(U/S BBC ACT/ U/S 111 TP ACT) : EVICTION SUIT(U/S BBC ACT/ U/S	JHKH02
112	EXECUITION CASES : EXECUITION CASES	JHKH02
135	INJUNCTION O-39 , R1, 2 : INJUNCTION O-39 , R1, 2	JHKH02
35	INSOLVENCY : INSOLVENCY	JHKH02
97	INTERPLEADER SUIT : INTERPLEADER SUIT	JHKH02
109	LAND ACQUISITION CASES : LAND ACQUISITION CASES	JHKH02
146	Land Acquistion Case (M) : Land Acquistion Case (M)	JHKH02
5	LAND REF. : LAND REF.	JHKH02
110	LUNACY/ ADOPTION/ INSOLVANCY : LUNACY/ ADOPTION/ INSOLVANCY	JHKH02
38	MESNE PROFIT : MESNE PROFIT	JHKH02
105	MISC CASES a) O-9 , R-4  b) O-9, R-9 c) U/S 47CPC : MISC CASES a) O-9 , R-4  b) O-	JHKH02
208	MISC. CIVIL APPLICATION : MISC. CIVIL APPLICATION	JHKH02
183	Misc(non-judl.)cases : Misc(Non-Judicial)Cases	JHKH02
158	Mislaneous : Mislaneous	JHKH02
171	Ms. Petition : misc. petition	JHKH02
103	ORIGINAL (MONEY) SUIT : ORIGINAL (MONEY) SUIT	JHKH02
143	Original (Title/Partition/Money) Suit : Original (Title/Partition/Money) Suit	JHKH02
102	PARTION SUIT / TITLE PARTION SUIT : PARTION SUIT / TITLE PARTION S	JHKH02
45	PAUPER APPLICATION : PAUPER APPLICATION	JHKH02
17	REG. CIVIL SUIT : REG. CIVIL SUIT	JHKH02
42	RENT SUIT : RENT SUIT	JHKH02
120	RESTORATION OF SUITS 0-9 R-4 , 0-9 , R -9 : RESTORATION OF SUITS 0-9 R-4 ,	JHKH02
121	SETTING  ASIDE EXPARTE ORDER 0-9 - R -13 : SETTING  ASIDE EXPARTE ORDER 0	JHKH02
41	SMALL CAUSE SUIT : SMALL CAUSE SUIT	JHKH02
19	SPL.CIV. SUIT : SPL.CIV. SUIT	JHKH02
94	SUIT BNY PERSON OF UNSOIUND MIND : SUIT BNY PERSON OF UNSOIUND MI	JHKH02
92	SUIT BY  CORPORATION/FIRM/TRUSTEE/INDEGENT PERSON : SUIT BY  CORPORATION/FIRM/TRUS	JHKH02
93	SUIT BY MINOR : SUIT BY MINOR	JHKH02
91	SUITE BY GOVT OR PUBLIC  OFFICERS IN OFFICIAL CAPA : SUITE BY GOVT OR PUBLIC  OFFIC	JHKH02
101	SUIT FOR PERPERTUAL INJECTION : SUIT FOR PERPERTUAL INJECTION	JHKH02
99	SUIT FOR SPECIAL PERFORMANCE OF CONTRACT : SUIT FOR SPECIAL PERFORMANCE O	JHKH02
96	SUIT OF PUBLIC NAUSIANCE : SUIT OF PUBLIC NAUSIANCE	JHKH02
95	SUMMARY SUIT : SUMMARY SUIT	JHKH02
175	Title (a)suit : Title Arbitration Suit	JHKH02
154	Title Mortgage Suit : Title Mortgage Suit	JHKH02
200	T N(tm) S : Title (Ttrade Mark ) Suit	JHKH02
174	T(p)suit : Title(partion)Suit	JHKH02
28	Bail Petition : BAIL PETITION.	JHHB03
159	Complain Case (O) : Complain Case (O)	JHHB03
166	Complain Cases : COMPLAIN CASES	JHHB03
142	Cr. Case Complaint (P) : Cr. Case Complaint (P)	JHHB03
24	CRI. BAIL APPLN. : CRI. BAIL APPLN.	JHHB03
157	Criminal Misc. Case : Criminal Miscllenous Case	JHHB03
178	Cyber Crime Case : Cyber Crime Case	JHHB03
80	DISTRESS WARRENT : DISTRESS WARRENT	JHHB03
147	Domestic Violence Act 2005 : Domestic Violence Act 2005	JHHB03
16	E.C. Cases : E.C. Cases	JHHB03
8	ELECT. PETN : ELECT. PETN	JHHB03
32	E.S.I. ACT  CASE : E.S.I. ACT  CASE	JHHB03
149	Excise Act : Excise Act	JHHB03
172	Forest Act Cases : FOREST ACT CASES	JHHB03
21	G. R Cases : G. R Cases	JHHB03
177	Juvenile Cases : Juvenile Cases	JHHB03
146	Land Acquisition : Land Acquisition	JHHB03
38	MESNE PROFIT : MESNE PROFIT	JHHB03
176	Misc. Criminal Application : Misc. Criminal Application	JHHB03
30	OTHER MISC. CRI. APPLN. : OTHER MISC. CRI. APPLN.	JHHB03
31	REVIEW APPLICATION : REVIEW APPLICATION	JHHB03
10	SESSION CASE : SESSION CASE	JHHB03
\.


--
-- Data for Name: district_court_order_copy_applicant_registration; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.district_court_order_copy_applicant_registration (application_id, application_number, cino, district_code, establishment_code, applicant_name, mobile_number, email, case_type, case_number, case_year, filing_number, filing_year, case_status, request_mode, applied_by, advocate_registration_number, petitioner_name, respondent_name, document_status, deficit_amount, deficit_status, deficit_payment_status, payment_status, certified_copy_ready_status, user_id, created_by, updated_by, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: district_court_order_copy_application_number_tracker; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.district_court_order_copy_application_number_tracker (id, date_key, counter, dist_code) FROM stdin;
\.


--
-- Data for Name: district_court_order_details; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.district_court_order_details (order_id, application_number, order_number, order_date, case_number, filing_number, number_of_page, amount, file_name, upload_status, certified_copy_uploaded_date, new_page_no, new_page_amount) FROM stdin;
\.


--
-- Data for Name: district_master; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.district_master (dist_code, dist_name) FROM stdin;
355	Bokaro
347	Chatra
350	Deoghar
354	Dhanbad
362	Dumka
346	Garhwa
349	Giridih
351	Godda
366	Gumla
360	Hazaribagh
363	Jamtara
365	Khunti
359	Latehar
356	Lohardaga
353	Pakur
358	Palamu
368	Pashchimi Singhbhum
357	Purbi Singhbhum
361	Ramgarh
364	Ranchi
352	Sahibganj
369	Saraikela - Kharsawan
367	Simdega
348	koderma
\.


--
-- Data for Name: districts_case_type; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.districts_case_type (case_type, type_name, ltype_name, full_form, lfull_form, type_flag, filing_no, filing_year, reg_no, reg_year, display, petitioner, respondent, lpetitioner, lrespondent, res_disp, case_priority, national_code, macp, stage_id, matter_type, cavreg_no, cavreg_year, direct_reg, cavfil_no, cavfil_year, ia_filing_no, ia_filing_year, ia_reg_no, ia_reg_year, tag_courts, amd, create_modify, est_code_src, reasonable_dispose, hideparty, imovable_suit, sec_hash_key, case_type_jurisdiction) FROM stdin;
1	Title Appl.	NULL	Title Appl.	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
2	Misc. Civ. Appl.	NULL	Misc. Civ. Appl.	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
3	CIVIL SUIT	NULL	CIVIL SUIT	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
4	MARRIAGE PETN.	NULL	MARRIAGE PETN.	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
5	LAND REF.	NULL	LAND REF.	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
6	DARKHAST PETITIONER	NULL	DARKHAST PETITIONER	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
7	L.R  DKST.	NULL	L.R  DKST.	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
8	ELECT. PETN	NULL	ELECT. PETN	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
9	C.APPLN.	NULL	C.APPLN.	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
10	Sess. Case	NULL	Sess. Case	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
11	SPL. CASE	NULL	SPL. CASE	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
12	Cri. Appeal	NULL	Cr. Appl.	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
13	Cri. Rev		Cr. Rev. 		2	0	2016	0	2024	N					0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
14	CRI. M.A.	NULL	CRI. M.A.	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
15	N.d.p.s Cases	NULL	N.D.P.S CASES	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
16	E.C.ACT.SPL.CASE	NULL	E.C.ACT.SPL.CASE	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
17	REG. CIVIL SUIT	NULL	REG. CIVIL SUIT	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
18	MUNCI. APPEAL	NULL	MUNCI. APPEAL	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
19	SPL.CIV. SUIT	NULL	SPL.CIV. SUIT	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
20	REG. CRI. CASE	NULL	REG. CRI. CASE	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
21	G.R. Trial		G.R. Trial		2	0	2016	0	2024	N					0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
22	SUMMARY CASE	NULL	SUMMARY CASE	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
23	ARBITRATION CASE	NULL	ARBITRATION CASE	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
24	CRI. BAIL APPLN.	NULL	CRI. BAIL APPLN.	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
25	SPL. CRI. MA	NULL	SPL. CRI. MA	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
26	N.d.p.s	NULL	N.D.P.S	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
27	NDPS CREI. REVN.	NULL	NDPS CREI. REVN.	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
28	REGULAR PETITION	NULL	REGULAR PETITION	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
29	Sc/st	NULL	S.C/S.T	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
30	Title Decl. Suit	NULL	Title Decl. Suit	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
31	REVIEW APPLICATION	NULL	REVIEW APPLICATION	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
32	E.S.I. ACT  CASE	NULL	E.S.I. ACT  CASE	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
33	FINAL DECREE	NULL	FINAL DECREE	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
34	Civil Misc (m.v)	NULL	Civil Misc (M.V)	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
35	Title Part. Suit	NULL	Title Part. Suit	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
36	PROBATE	NULL	PROBATE	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
37	GUARDIAN AND WARDS CASE		GUARDIAN AND WARDS CASE		2	0	0	0	2024	N					0	0	5014	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
38	MESNE PROFIT	NULL	MESNE PROFIT	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
39	SUCCESSION	NULL	SUCCESSION	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
40	SPL. CASE( MSEB)	NULL	SPL. CASE( MSEB)	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
41	SMALL CAUSE SUIT	NULL	SMALL CAUSE SUIT	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
42	RENT SUIT	NULL	RENT SUIT	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
43	TRUST APPEAL	NULL	TRUST APPEAL	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
44	TRUST SUIT	NULL	TRUST SUIT	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
45	PAUPER APPLICATION	NULL	PAUPER APPLICATION	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
46	SPL. MARRIAGE PETITION	NULL	SPL. MARRIAGE PETITION	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
47	ARBITRATION R.D.	NULL	ARBITRATION R.D.	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
48	CONTEMPT PROCEEDINGS	NULL	CONTEMPT PROCEEDINGS	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
76	RENT APPEAL	NULL	RENT APPEAL	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
80	DISTRESS WARRENT	NULL	DISTRESS WARRENT	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
81	SUM. CIVIL SUIT	NULL	SUM. CIVIL SUIT	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
89	ADMINISTRATIVE SUITE	NULL	ADMINISTRATIVE SUITE	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
90	DISSOLUTION OF PATNERSHIP	NULL	DISSOLUTION OF PATNERSHIP	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
91	SUITE BY GOVT OR PUBLIC  OFFICERS IN OFFICIAL CAPA	NULL	SUITE BY GOVT OR PUBLIC  OFFIC	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
92	SUIT BY  CORPORATION/FIRM/TRUSTEE/INDEGENT PERSON	NULL	SUIT BY  CORPORATION/FIRM/TRUS	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
93	SUIT BY MINOR	NULL	SUIT BY MINOR	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
94	SUIT BNY PERSON OF UNSOIUND MIND	NULL	SUIT BNY PERSON OF UNSOIUND MI	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
95	SUMMARY SUIT	NULL	SUMMARY SUIT	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
96	SUIT OF PUBLIC NAUSIANCE	NULL	SUIT OF PUBLIC NAUSIANCE	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
97	INTERPLEADER SUIT	NULL	INTERPLEADER SUIT	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
98	CROSS SUIT	NULL	CROSS SUIT	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
99	SUIT FOR SPECIAL PERFORMANCE OF CONTRACT	NULL	SUIT FOR SPECIAL PERFORMANCE O	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
100	DECLARATIVCE SUIT	NULL	DECLARATIVCE SUIT	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
101	SUIT FOR PERPERTUAL INJECTION	NULL	SUIT FOR PERPERTUAL INJECTION	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
102	PARTION SUIT / TITLE PARTION SUIT	NULL	PARTION SUIT / TITLE PARTION S	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
103	Money Suit	NULL	Money Suit	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
104	Evic. Suit	NULL	Evic. Suit	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
105	Misc Cases		Misc. Case		1	0	0	0	2024	N					0	0	5014	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
106	Claim Cases	NULL	Claim	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
107	Original Suit		Original Suit		1	0	0	125	2024	Y					0	0	5001	N	NULL	4	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
108	Probate Case	NULL	Probate	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
109	Land Acquistaion Cases	NULL	L.A	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
110	LUNACY/ ADOPTION/ INSOLVANCY	NULL	LUNACY/ ADOPTION/ INSOLVANCY	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
111	SUCESSION CERTIFICATE CASES	NULL	SUCESSION CERTIFICATE CASES	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
112	Execution Cases		EXECUTION CASES		1	0	0	0	2024	N					0	0	5006	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
113	SUIT OF ENCHORMENT	NULL	SUIT OF ENCHORMENT	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
114	APPEAL -MISC A) INJECTION , B) RECEIVERSHIP , 3) O	NULL	APPEAL -MISC A) INJECTION , B)	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
115	MISJOINDER & NON JOINDER -0-1 , R-9	NULL	MISJOINDER & NON JOINDER -0-1 	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
116	STRIKE OUT OR ADD PARTIES -0-1 , R -10	NULL	STRIKE OUT OR ADD PARTIES -0-1	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
117	OBJECTION TO NON -JOINDER & MIS JOINDER -0-1 , R 1	NULL	OBJECTION TO NON -JOINDER & MI	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
118	STRIKING OUT PLEADING -0-6 , R-16	NULL	STRIKING OUT PLEADING -0-6 , R	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
119	AMENDENT OF PLEADINGS -0-6-R-17	NULL	AMENDENT OF PLEADINGS -0-6-R-1	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
120	RESTORATION OF SUITS 0-9 R-4 , 0-9 , R -9	NULL	RESTORATION OF SUITS 0-9 R-4 ,	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
121	SETTING  ASIDE EXPARTE ORDER 0-9 - R -13	NULL	SETTING  ASIDE EXPARTE ORDER 0	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
122	INTERROGATURIES  0-11 , R -1	NULL	INTERROGATURIES  0-11 , R -1	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
123	DISCOVERY OF DOCUMENTS 0-11, R-12	NULL	DISCOVERY OF DOCUMENTS 0-11, R	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
124	INSPECTION OF DOCUMENTS -0-11 , R-15	NULL	INSPECTION OF DOCUMENTS -0-11 	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
125	PRELIMINARY ISSUES 0-14	NULL	PRELIMINARY ISSUES 0-14	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
126	RECALL OF WITNESS -0-18 , R-17	NULL	RECALL OF WITNESS -0-18 , R-17	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
127	SUBSTITUTION 0-22 R -3 & 4	NULL	SUBSTITUTION 0-22 R -3 & 4	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
128	WITHDRAWAL OF SUITS 0-23 , R1	NULL	WITHDRAWAL OF SUITS 0-23 , R1	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
129	TRANSPOSITION OF DEFENDENT  AS PLAINTIFF  0-23 RUL	NULL	TRANSPOSITION OF DEFENDENT  AS	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
130	COMPROMISE PETITIONER 0-23 R-3	NULL	COMPROMISE PETITIONER 0-23 R-3	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
131	ISSUANCE OF COMMISON 0-26 , R -2 , 9 , 13	NULL	ISSUANCE OF COMMISON 0-26 , R 	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
132	APPOINTMENT OF GUARDINA FOR MINOR O-32 , R-3	NULL	APPOINTMENT OF GUARDINA FOR MI	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
133	MESNS PROFIT 0-34 , R-10A	NULL	MESNS PROFIT 0-34 , R-10A	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
134	ATTACHMENT BEFORE JUDGEMNT O-38 RULE 5	NULL	ATTACHMENT BEFORE JUDGEMNT O-3	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
135	INJUNCTION O-39 , R1, 2	NULL	INJUNCTION O-39 , R1, 2	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
136	APPOINTMENT OF RECIVER 0-40, R	NULL	APPOINTMENT OF RECIVER 0-40, R	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
137	REVIEW/RECALL PET. U/S-114CPC	NULL	REVIEW/RECALL PET. U/S-114CPC	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
138	APP. OF RES. SUBJUDICE U/S 10	NULL	APP. OF RES. SUBJUDICE U/S 10	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
139	APP. OF RESJUDICATE U/S 11 CPC	NULL	APP. OF RESJUDICATE U/S 11 CPC	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
140	Money Appeal	NULL	M.A	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
141	Indian Penal Code (ipc)	NULL	INDIAN PENAL CODE (IPC)	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
142	Cr. Case Com(o)	NULL	Comp.(O)	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
143	Title Suit	NULL	T.S	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
145	SUCCESSION	NULL	SUCCESSION	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
146	L.A	NULL	L.A	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
147	Domestic Violence Act 2005	NULL	DOMESTIC VIOLENCE ACT 2005	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
148	W & M Act	NULL	WEIGHT & MEASUREMENT ACT	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
149	Excise Act	NULL	EXCISE ACT	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
150	Police Act 2007	NULL	Police Act 2007	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
151	Cr. Juv. Appl	NULL	Cr. Juv. Appl.	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
152	Anti Bail Petition		A.B.P		2	0	2016	0	2024	N					0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
153	Bail Petition	NULL	B.P	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
154	T.m.s	NULL	TITLE MORTGAGE SUIT	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
155	T.e.s	NULL	Title Evic. Suit	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
156	Ori. Maint. Case		Original Maintenance Case		2	0	0	84	2024	Y	Petitioner	Respondent			0	0	6002	N	NULL	4	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-17 16:49:40.42099	JHCH05	NULL	N	N	NULL	Y
157	Misc. Cr.		Misc. Cr.		2	6	2016	0	2024	N					0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
158	Misc. Civ	NULL	Civ. Misc.	NULL	1	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
159	Cr. Case Com(p)	NULL	Comp.(p)	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
160	Weight & Measurement Act 1985	NULL	WEIGHT & MEASUREMENT ACT 1985	NULL	2	0	2016	0	2024	N			NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
161	Guardianship Case		Guardianship Case		1	0	2016	0	2024	N					0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
162	U.c Case	NULL	U.C	NULL	2	0	2016	0	2024	N	Prosecution	Accused	NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
163	Partition Appeal	NULL	Partition Appeal	NULL	1	0	2016	0	2024	N	Plaintiff Name	Defendent Name	NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
164	Partition Suit	NULL	P.S	NULL	1	0	2016	0	2024	N	Plaintiff Name	Defendent Name	NULL	NULL	0	0	0	N	NULL	0	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
165	Civil Misc. Case		Civil Misc. Case		1	0	0	0	2024	Y	Petitioner	Respondent			0	0	5014	N	NULL	3	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
166	Maintenance Alt. Case		Maintenance Alteration Case		2	0	0	0	2024	Y					0	0	6007	N	NULL	4	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
167	Misc. Cri. Application		Misc. Cri. Application		2	0	0	18	2024	Y	Petitioner	Respondent			0	0	6007	N	NULL	3	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
168	Misc. Civil Application		Misc. Civil Application		1	0	0	20	2024	Y	Petitioner	Respondent			0	0	5016	N	NULL	3	0	0	N	0	0	0	0	0	0	NULL	U	2024-12-16 16:55:13.853152	JHCH05	NULL	N	N	NULL	Y
\.


--
-- Data for Name: establishment; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.establishment (id, est_code, estname, dist_code) FROM stdin;
2	JHBK04	Civil Judge Junior Division	355
11	JHDM02	Civil Judge Senior Division	362
12	JHDM01	District and Sessions Court	362
13	JHDM05	Family Court	362
14	JHDM03	Judicial Magistrate Courts	362
15	JHKD03	Chief Judicial Magistrate	348
16	JHKD04	Civil Judge Junior Division	348
17	JHKD02	Civil Judge Senior Division	348
18	JHKD01	District and Sessions Judge	348
19	JHKD05	Principal Judge Family Court	348
25	JHGM04	Civil Judge Junior Division	366
26	JHGM02	Civil Judge Senior Division	366
27	JHGM01	District and Sessions Judge	366
28	JHGM05	Family court	366
29	JHGM03	Judicial Magistrate	366
30	JHGD03	Chief Judicial Magistrate Courts	351
31	JHGD02	Civil Judge Senior Division Courts	351
32	JHGD01	District and Sessions Courts	351
33	JHGD05	Family Court	351
34	JHHB03	Chief Judicial Magistrate Hazaribag	360
35	JHHB04	Civil Judge Junior Division	360
36	JHHB02	Civil Judge Senior Division	360
37	JHHB05	Family Court	360
38	JHHB01	Principal District and Sessions Judge Hazaribag	360
39	JHLH03	Chief Judicial Magistrate, Lohardaga	356
40	JHLH04	Civil Judge Junior Division, Lohardaga	356
41	JHLH02	Civil Judge Senior Division, Lohardaga	356
42	JHLH05	Family Court	356
43	JHLH01	Principal District Judge, Lohardaga	356
44	JHDH03	Chief Judicial Magistrate	354
45	JHDH04	Civil Judge Junior Division	354
46	JHDH02	Civil Judge Senior Division	354
47	JHDH01	District and Sessions Court	354
48	JHDH05	Family Court	354
49	JHGR03	Chief Judicial Magistrate Courts Giridih	349
50	JHGR04	Civil Judge Junior Division Courts Giridih	349
51	JHGR02	Civil Judge Senior Division Courts Giridih	349
52	JHGR01	District and Sessions Courts Giridih	349
53	JHGR05	Family Court	349
64	JHJM02	Civil Judge Senior Division	363
65	JHJM01	District and Sessions Courts	363
66	JHJM05	Family Court	363
67	JHJM03	Judicial Magistrate Courts	363
68	JHCH02	Civil Judge Establishment	347
69	JHCH05	Family Court	347
70	JHCH03	Judicial Magistrate Establishment	347
71	JHCH01	Principal District Judge Establishment	347
72	JHPK03	Chief Judicial Magistrate Pakur	353
73	JHPK02	Civil Judge Senior Division Pakur	353
74	JHPK01	District and Sessions Court Pakur	353
75	JHPK05	Family Court	353
76	JHSK03	Chief Judicial Magistrate	369
77	JHSK04	Civil Judge Junior Division	369
78	JHSK02	Civil Judge Senior Division	369
79	JHSK01	District and Sessions Court	369
80	JHSK05	Family Court	369
81	JHDG02	Civil Judge Establishment	350
82	JHMP07	Civil Judge Establishment, Madhupur	350
83	JHMP06	District and Additional Session Court, Madhupur	350
84	JHDG05	Family Court	350
85	JHDG03	Judicial Magistrate Establishment	350
86	JHMP08	Judicial Magistrate Establishment, Madhupur	350
93	JHSD03	Chief Judicial Magistrate, Simdega	367
94	JHSD04	Civil Judge Junior Division, Simdega	367
95	JHSD02	Civil Judge Senior Division, Simdega	367
96	JHSD01	District And Sessions Court, Simdega	367
97	JHLT03	Chief Judicial Magistrate	359
98	JHLT04	Civil Judge Junior Division	359
99	JHLT02	Civil Judge Senior Division	359
100	JHLT01	District and Sessions Courts	359
101	JHLT05	Family court	359
102	JHGW03	Chief Judicial Magistrate Garhwa	346
88	JHPL03	Chief Judicial Magistrate	358
89	JHPL04	Civil Judge Junior Division	358
90	JHPL02	Civil Judge Senior Division	358
91	JHPL01	Principal District and Sessions Judge	358
92	JHPL05	Principal Judge Family Court	358
54	JHJR08	Additional Chief Judicial Magistrate Courts, Ghatshila	357
55	JHJR03	Chief Judicial Magistrate, Jamshedpur	357
56	JHJR09	Civil Judge Junior Division Courts, Ghatshila	357
57	JHJR04	Civil Judge Junior Division Courts, Jamshedpur	357
58	JHJR07	Civil Judge Senior Division Courts, Ghatshila	357
20	JHCB03	Chief Judicial Magistrate	368
21	JHCB02	Civil Judge Senior Division	368
22	JHCB01	District and Sessions Court	368
23	JHCB06	Judicial Magistrate Porahat	368
24	JHCB05	Principal Judge, Family Court	368
3	JHBT04	Civil Judge Junior Division, Tenughat, Tenughat	355
4	JHBK02	Civil Judge Senior Division	355
5	JHBT02	Civil Judge Senior Division, Tenughat	355
6	JHBT01	District and Sessions Court, Tenughat	355
7	JHBK05	Family Court	355
8	JHBT05	Family Court, Tenughat	355
9	JHBT03	Judicial Magistrate Court, Tenughat	355
10	JHBK01	Principal District and Sessions Judge	355
1	JHBK03	Chief Judicial Magistrate	355
87	JHDG01	Principal District and Sessions Judge Establishment	350
103	JHGW04	Civil Judge Jr Div Garhwa	346
104	JHGW02	Civil Judge Sr Div Garhwa	346
105	JHGW05	Family Court	346
106	JHGW01	Principal District and Sessions Judge Garhwa	346
107	JHSG02	Civil Judge, Sahibganj	352
108	JHSG07	Civil Judge Senior Division Rajmahal	352
109	JHSG06	District and Sessions Judge Rajmahal	352
110	JHSG09	Family Court Rajmahal	352
111	JHSG05	Family Court, Sahibganj	352
112	JHSG08	Judicial Magistrate Rajmahal	352
113	JHSG03	Judicial Magistrate, Sahibganj	352
114	JHSG01	Principal District and Sessions Judge, Sahibganj	352
115	JHRN03	Chief Judicial Magistrate	364
116	JHRN04	Civil Judge Junior Division	364
117	JHRN02	Civil Judge Senior Division	364
118	JHRN05	Family Court	364
119	JHRN01	Principal District and Session Judge	364
120	JHRG04	CIVIL JUDGE JUNIOR DIVISION	361
121	JHRG02	CIVIL JUDGE SENIOR DIVISION	361
122	JHRG01	DISTRICT AND SESSIONS JUDGE COURT	361
123	JHRG05	FAMILY COURT	361
124	JHRG03	MAGISTRATE COURT	361
125	JHKH03	Chief Judicial Magistrate	365
126	JHKH04	Civil Judge Junior Division	365
127	JHKH02	Civil Judge Senior Division	365
128	JHKH01	Principal District and Sessions Judge	365
59	JHJR02	Civil Judge Senior Division Courts, Jamshedpur	357
60	JHJR06	District and Additional Sessions Court, Ghatshila	357
61	JHJR01	District and Sessions Courts, Jamshedpur	357
62	JHJR10	Family Court Ghatshila	357
63	JHJR05	Family Court Jamshedpur	357
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: fee_master; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.fee_master (fee_id, fee_type, amount) FROM stdin;
2	per_page_fee	5.00
1	urgent_fee	5.00
\.


--
-- Data for Name: hc_order_copy_applicant_registration; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.hc_order_copy_applicant_registration (application_id, application_number, cino, applicant_name, mobile_number, email, case_type, case_number, case_year, filing_number, filing_year, case_status, request_mode, applied_by, advocate_registration_number, petitioner_name, respondent_name, document_status, deficit_amount, deficit_status, deficit_payment_status, payment_status, certified_copy_ready_status, user_id, created_by, updated_by, created_at, updated_at) FROM stdin;
52	HCW00314072501	JHHC010189682024	BSSR PRESENT SECRETARY SUMEET GUPTA	9304984077	bssr_sumeetgupta@gmail.com	3	354	2024	5457	2024	D	urgent	petitioner	\N	BSSR UNION REPRESENTED THROUGH ITS PRESENT SECRETARY SUMEET GUPTA	M/S SVIZERA HEALTHCARE THROUGH ITS MANAGING DIRECTOR	1	\N	0	0	1	1	3	system	\N	2025-07-14 17:46:28.796822	2025-07-14 17:48:54.070148
53	HCW05715072501	JHHC010130052015	TUSHAR ANAND	9304984055	tusharanand303@gmail.com	57	3743	2015	24118	2015	D	urgent	respondent	\N	BIKASH CHANDRA THAKUR	THE STATE OF JHARKHAND AND ANR	1	45.00	1	0	1	0	0	system	\N	2025-07-15 16:56:29.335744	2025-07-17 10:57:44.581541
54	HCW05715072502	JHHC010130052015	TEST	9304984055	test@gmail.com	57	3743	2015	24118	2015	D	urgent	advocate	ADVRES12345	BIKASH CHANDRA THAKUR	THE STATE OF JHARKHAND AND ANR	1	45.00	1	0	1	0	0	system	\N	2025-07-15 17:52:01.826949	2025-07-17 11:07:32.747107
55	HCW05717072501	JHHC010130052015	TUSHAR ANAND	9304984077	tusharanand303@gmail.com	57	3743	2015	24118	2015	D	urgent	others	\N	BIKASH CHANDRA THAKUR	THE STATE OF JHARKHAND AND ANR	1	\N	0	0	1	1	3	system	\N	2025-07-17 11:09:03.264396	2025-07-17 11:12:06.816098
\.


--
-- Data for Name: hc_order_copy_application_number_tracker; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.hc_order_copy_application_number_tracker (date_key, counter) FROM stdin;
2025-07-14	1
2025-07-15	2
2025-07-17	1
\.


--
-- Data for Name: hc_order_details; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.hc_order_details (order_id, application_number, order_number, order_date, case_number, filing_number, number_of_page, amount, file_name, upload_status, certified_copy_uploaded_date, new_page_no, new_page_amount) FROM stdin;
105	HCW00514072501	1	2025-01-16	21	26015	1	5.00	HCW00514072501_1_1752492507.pdf	t	2025-07-14 16:58:27	1	5.00
106	HCW00314072501	1	2025-04-11	354	5457	1	5.00	HCW00314072501_1_1752495502.pdf	t	2025-07-14 17:48:22	1	5.00
107	HCW00314072501	3	2025-04-22	354	5457	1	5.00	HCW00314072501_3_1752495520.pdf	t	2025-07-14 17:48:40	1	5.00
108	HCW05715072501	2	2017-01-13	3743	24118	1	5.00	HCW05715072501_2_1752729833.pdf	t	2025-07-17 10:53:53	10	50.00
109	HCW05715072501	4	2015-11-23	3743	24118	1	5.00	HCW05715072501_4_1752729840.pdf	t	2025-07-17 10:54:00	1	5.00
110	HCW05715072501	7	2016-05-20	3743	24118	1	5.00	HCW05715072501_7_1752730055.pdf	t	2025-07-17 10:57:35	1	5.00
111	HCW05715072501	10	2016-09-26	3743	24118	1	5.00	HCW05715072501_10_1752730060.pdf	t	2025-07-17 10:57:40	1	5.00
112	HCW05715072502	1	2016-09-27	3743	24118	1	5.00	HCW05715072502_1_1752730620.pdf	t	2025-07-17 11:07:00	10	50.00
113	HCW05715072502	4	2015-11-23	3743	24118	1	5.00	HCW05715072502_4_1752730626.pdf	t	2025-07-17 11:07:06	1	5.00
114	HCW05715072502	7	2016-05-20	3743	24118	1	5.00	HCW05715072502_7_1752730649.pdf	t	2025-07-17 11:07:29	1	5.00
115	HCW05717072501	1	2016-09-27	3743	24118	1	5.00	HCW05717072501_1_1752730907.pdf	t	2025-07-17 11:11:47	1	5.00
116	HCW05717072501	3	2017-02-07	3743	24118	1	5.00	HCW05717072501_3_1752730921.pdf	t	2025-07-17 11:12:01	1	5.00
\.


--
-- Data for Name: hc_users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.hc_users (id, name, email, mobile_no, role_id, username, password, created_at) FROM stdin;
4	Rahul Thakur	rahul.thakur.bca@gmail.com	8252696364	4	rahul_thakur	$2y$10$SQyD.si0MfcWz84ZMa2bV.n9EhpJsTVagV4Ok22s55cY4A4.lOKbe	2025-06-05 17:04:28.868992
5	Gautam Kumar	gautam@gmail.com	8076472041	5	gautam_kumar	$2y$10$hCZ/NyQHGhFBxVZ7a0vf8OjaXYu5fqU4TSYtomSdmKy12/Upj0hVa	2025-06-05 17:07:08.334424
3	Tushar Anand	tusharanand303@gmail.com	9090909090	1	tushar	$2y$12$131auqP4u68F866H44xRqO03V7vhPpvxy2ld38bFilu9fbPJgL7XG	2025-02-27 16:50:14.794
\.


--
-- Data for Name: high_court_applicant_registration; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.high_court_applicant_registration (application_id, application_number, applicant_name, mobile_number, email, case_type, case_filling_number, case_filling_year, selected_method, request_mode, required_document, applied_by, advocate_registration_number, document_status, payment_status, certified_copy_ready_status, rejection_status, rejection_remarks, rejected_by, rejection_date, created_by, updated_by, created_at, updated_at) FROM stdin;
46	HC08216072501	Tushar Anand	9304984077	tusharanand303@gmail.com	82	3743	2015	C	ordinary	test	respondent	\N	0	3	0	0	\N	0	\N	system	\N	2025-07-16 15:59:32.034439	2025-07-16 15:59:32.034439
\.


--
-- Data for Name: high_court_application_number_tracker; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.high_court_application_number_tracker (date_key, counter) FROM stdin;
2025-07-14	1
2025-07-16	1
\.


--
-- Data for Name: high_court_case_master; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.high_court_case_master (case_type, type_name) FROM stdin;
61	A.APPL : ARBITRATION APPLICATION
57	A.B.A. : ANTICIPATORY BAIL
65	A.C.(D.B.) : APPEAL CASE (DIVISION BENCH)
67	Acq. App. : ACQUITTAL APPEAL
82	Acq. App.(C) : ACQUITTAL APPEAL (COMPLAINANT)
66	A.C.(S.B.) : APPEAL CASE (SINGLE BENCH)
62	Arb. Appeal : ARBITRATION APPEAL
56	B.A. : REGULAR BAIL
20	C.A. : COMPANY APPEAL
58	C.M.P. : CIVIL MISCELLENOUS PETITION
52	C.O. : CROSS OBJECTIONS
81	COM.APPEAL : COMMERCIAL APPEAL
34	COMP. APL. : COMPENSATION APPEAL
37	COMP.AP.SJ : COMPANY APPEAL (SINGLE JUDGE)
39	CONT.APP. : CONTEMPT APPEAL
54	Cont.(Crl) : CRIMINAL CONTEMPT
55	Cont.(Cvl) : CIVIL CONTEMPT
42	Cop.Lnt : COMPLAINT APPEAL
21	C.P. : COMPANY PETITION/APPLICATION
8	C.R. : CIVIL REVISION
5	Cr.A(DB) : CRIMINAL APPEAL (DIVISION BENCH)
35	Cr.App(341) : CRIMINAL APPEAL 341 CODE OF CRIMINAL PROCEDURE
27	CrApp(U/S) : CRIMINAL APPEAL (UNDER SECTION)
83	Cr.App.(V) : CRIMINAL APPEAL (VICTIM)
24	Cr.A(SJ) : CRIMINAL APPEAL (SINGLE JUDGE)
12	C Ref : CIVIL REFERENCE
11	C.Rev. : CIVIL REVIEW
6	Cr.Misc. : CRIMINAL MISCELLENOUS
53	Cr.M.P. : CRIMINAL MISCELLENOUS PETITION
29	Cr. Ref. : CRIMINAL REFERENCE
7	Cr.Rev. : CRIMINAL REVISION
16	Cr.WJC : Cr.WJC(CRIMINAL WRIT)
15	CWJC : CWJC(CIVIL WRIT)
28	D. Ref. : DEATH REFERENCE
14	E.P. : ELECTION PETITION
1	FA : FIRST APPEAL
25	G.App.(DB) : GOVERNMENT APPEAL (DIVISION BENCH)
31	G.App.(SJ) : GOVERNMENT APPEAL (SINGLE JUDGE)
59	L.A. : LETTER ADMIN
3	LPA : LETTER PATENTS APPEAL
2	MA : MISCELLENOUS APPEAL
19	Mat Ref : MATRIMONIAL REFERENCE
33	Mat.Suit : MATRIMONIAL SUIT
10	MJC : MISCELLANEOUS JURISDICTION CASES
32	OCrMisc(DB : ORIGINAL CRIMINAL MISCELLENOUS (DIVISION BENCH)
23	Or.Cr.Misc : ORIGINAL CRIMINAL MISCELLENOUS
86	Original Suit (Under Patents Act) : Original Suit (Under Patents Act)
60	PROB.CASE : PROB.CASE
74	REQ.APPEAL : REQUEST APPEAL
36	Req. Case : REQUEST CASE
9	SA : SECOND APPEAL
40	SCA : SUPREME COURT APPEAL
85	S.C.L.P(Cr.) : SUPREME COURT LEAVE PETITION (CRIMINAL)
68	S.C.L.P(Cvl) : SUPREME COURT LEAVE PETITION (CIVIL)
26	SLA : SPECIAL LEAVE TO APPEAL (CRIMINAL)
43	T.A. : TAX APPEAL
4	Tax : TAX CASES
73	TAX APPLI. : TAX APPLICATION
17	Test Case : TEST CASE
64	Tr.Pet.Crl : CRIMINAL TRANSFER PETITION
63	Tr.Pet.CVL : CIVIL TRANSFER PETITION
22	T.S. : TESTAMENTARY SUIT
50	WPC : WRIT PETITIONS
51	W.P.(Cr.) : CRIMINAL WRIT PETITIONS
\.


--
-- Data for Name: high_court_case_type; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.high_court_case_type (case_type, type_name, ltype_name, full_form, lfull_form, type_flag, filing_no, filing_year, reg_no, reg_year, display, petitioner, respondent, lpetitioner, lrespondent, res_disp, case_priority, national_code, macp, stage_id, matter_type, cavreg_no, cavreg_year, direct_reg, cavfil_no, cavfil_year, ia_filing_no, ia_filing_year, ia_reg_no, ia_reg_year, tag_courts, amd, create_modify, reasonable_dispose, hideparty, est_code_src) FROM stdin;
29	Cr. Ref.		CRIMINAL REFERENCE		2	0	0	0	2025	Y	Petitioner	Respondent			0	0	1007031	N	\N	0	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
77	M A C CASE	\N	Motor Accident Claims	\N	1	0	2017	0	2025	N	 	 	\N	\N	0	0	1004024	N	\N	0	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
13	Comp.Appeal	\N	COMPANY APPEAL	\N	1	0	2017	0	2025	N	Petitioner 	Respondent 	\N	\N	0	0	1004007	N	\N	0	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
21	C.P.		COMPANY PETITION/APPLICATION		1	0	0	0	2025	Y	Petitioner	Respondent			0	0	1010007	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
59	L.A.		LETTER ADMIN		1	0	0	0	2025	Y	Petitioner	Respondent			0	0	1005037	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
62	Arb. Appeal		ARBITRATION APPEAL		1	0	0	0	2025	Y	Petitioner	Respondent			0	0	1004004	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
37	COMP.AP.SJ		COMPANY APPEAL (SINGLE JUDGE)		1	0	0	0	2025	Y	Petitioner	Respondent			0	0	1004007	N	\N	0	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
39	CONT.APP.		CONTEMPT APPEAL		1	0	0	0	2025	Y	Petitioner	Respondent			0	0	1004008	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
54	Cont.(Crl)		CRIMINAL CONTEMPT		2	0	0	0	2025	Y	Petitioner	Respondent			0	0	1005008	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
64	Tr.Pet.Crl		CRIMINAL TRANSFER PETITION		2	0	0	0	2025	Y	Petitioner	Respondent			0	0	1010025	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
78	CR. EXE.	\N	CRIMINAL EXECUTION CASE	\N	2	0	2017	0	2025	N	 	 	\N	\N	0	0	1005013	N	\N	0	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
85	S.C.L.P(Cr.)		SUPREME COURT LEAVE PETITION (CRIMINAL)		2	0	0	0	2025	Y	Petitioner	Respondent			0	0	0	N	\N	4	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
35	Cr.App(341)		CRIMINAL APPEAL 341 CODE OF CRIMINAL PROCEDURE		2	0	0	0	2025	Y	Petitioner	Respondent			0	0	1004025	N	\N	0	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
86	Original Suit (Under Patents Act)		Original Suit (Under Patents Act)		1	0	0	0	2025	Y	Petitioner	Respondent			0	0	0	N	\N	4	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
83	Cr.App.(V)		CRIMINAL APPEAL (VICTIM)		2	0	0	0	2025	Y					0	0	1004031	N	\N	2	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
61	A.APPL		ARBITRATION APPLICATION		1	0	0	0	2025	Y	Petitioner	Respondent			0	0	1010004	N	\N	2	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
81	COM.APPEAL	\N	COMMERCIAL APPEAL	\N	1	0	2017	0	2025	Y	 	 	\N	\N	0	0	1004038	N	\N	0	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
17	Test Case	\N	TEST CASE	\N	1	0	2017	0	2025	Y	Petitioner 	Respondent 	\N	\N	0	0	1008026	N	\N	0	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
68	S.C.L.P(Cvl)		SUPREME COURT LEAVE PETITION (CIVIL)		1	0	0	0	2025	Y	Petitioner	Respondent			0	0	1010022	N	\N	0	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
31	G.App.(SJ)		GOVERNMENT APPEAL (SINGLE JUDGE)		2	0	0	0	2025	Y	Petitioner	Respondent			0	0	1004014	N	\N	0	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
18	Test Suit	\N	TEST SUIT	\N	1	0	2017	0	2025	N	Petitioner 	Respondent 	\N	\N	0	0	1008026	N	\N	0	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
16	Cr.WJC		Cr.WJC(CRIMINAL WRIT)		2	0	0	0	2025	Y	Petitioner	Respondent			0	0	1001031	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
32	OCrMisc(DB		ORIGINAL CRIMINAL MISCELLENOUS (DIVISION BENCH)		2	0	0	0	2025	Y	Petitioner	Respondent			0	0	1005025	N	\N	0	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
19	Mat Ref		MATRIMONIAL REFERENCE		1	0	0	0	2025	Y	Petitioner	Respondent			0	0	1007037	N	\N	0	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
12	C Ref		CIVIL REFERENCE		1	0	0	0	2025	Y	Petitioner	Respondent			0	0	1007031	N	\N	0	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
42	Cop.Lnt		COMPLAINT APPEAL		2	0	0	0	2025	Y	Petitioner	Respondent			0	0	1004027	N	\N	0	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
28	D. Ref.		DEATH REFERENCE		2	0	0	0	2025	Y	Petitioner	Respondent			0	0	1007011	N	\N	0	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
23	Or.Cr.Misc		ORIGINAL CRIMINAL MISCELLENOUS		2	0	0	0	2025	Y	Petitioner	Respondent			0	0	1005025	N	\N	0	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
36	Req. Case		REQUEST CASE		1	0	0	0	2025	Y	Petitioner	Respondent			0	0	1005033	N	\N	0	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
82	Acq. App.(C)		ACQUITTAL APPEAL (COMPLAINANT)		2	0	0	4	2025	Y	Petitioner	Respondent			0	0	1004001	N	\N	2	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-06 15:12:45.604629	\N	N	JHHC01
14	E.P.		ELECTION PETITION		1	0	0	0	2025	Y	Petitioner	Respondent			0	0	1005012	N	\N	0	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
25	G.App.(DB)		GOVERNMENT APPEAL (DIVISION BENCH)		2	0	0	0	2025	Y	Petitioner	Respondent			0	0	1004014	N	\N	0	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
41	Money SUIT	\N	MONEY SUIT	\N	1	0	2017	0	2025	N	Petitioner 	Respondent 	\N	\N	0	0	1008026	N	\N	0	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
38	Comp.Ap.DB	\N	COMPANY APPEAL (DIVISION BENCH)	\N	1	0	2017	0	2025	N	Petitioner 	Respondent 	\N	\N	0	0	1004007	N	\N	0	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
84	Acq. App.(V)		ACQUITTAL APPEAL (VICTIM)		2	0	0	0	2025	N	Petitioner	Respondent			0	0	1004001	N	\N	2	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
74	REQ.APPEAL		REQUEST APPEAL		1	0	0	0	2025	Y	Petitioner	Respondent			0	0	1004033	N	\N	0	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
30	PATENT CAS	\N	PATENT CASE	\N	1	0	2017	0	2025	N	Petitioner 	Respondent 	\N	\N	0	0	1008033	N	\N	0	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
34	COMP. APL.		COMPENSATION APPEAL		1	0	0	0	2025	Y	Petitioner	Respondent			0	0	1004024	N	\N	0	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
22	T.S.		TESTAMENTARY SUIT		1	0	0	0	2025	Y	Petitioner	Respondent			0	0	1008037	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
27	CrApp(U/S)		CRIMINAL APPEAL (UNDER SECTION)		2	0	0	0	2025	Y	Petitioner	Respondent			0	0	1004031	N	\N	0	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
6	Cr.Misc.		CRIMINAL MISCELLENOUS		2	0	0	0	2025	Y	Petitioner	Respondent			0	0	1005025	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
4	Tax		TAX CASES		1	0	0	0	2025	Y	Petitioner	Respondent			0	0	1004036	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
52	C.O.		CROSS OBJECTIONS		1	0	0	0	2025	Y	Petitioner	Respondent			0	0	1004010	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
26	SLA		SPECIAL LEAVE TO APPEAL (CRIMINAL)		2	0	0	0	2025	Y	Petitioner	Respondent			0	0	1010022	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
3	LPA		LETTER PATENTS APPEAL		1	0	0	11	2025	Y	Petitioner	Respondent			0	0	1004023	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-04 11:57:18.087558	\N	N	JHHC01
43	T.A.		TAX APPEAL		1	0	0	0	2025	Y	Petitioner	Respondent			0	0	1004036	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
15	CWJC		CWJC(CIVIL WRIT)		1	0	0	0	2025	Y	Petitioner	Respondent			0	0	1001031	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
40	SCA		SUPREME COURT APPEAL		1	0	0	0	2025	Y	Petitioner	Respondent			0	0	1004033	N	\N	0	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
10	MJC		MISCELLANEOUS JURISDICTION CASES		1	0	0	0	2025	Y	Petitioner	Respondent			0	0	1005025	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
73	TAX APPLI.		TAX APPLICATION		1	0	0	0	2025	Y	Petitioner	Respondent			0	0	1010036	N	\N	0	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
33	Mat.Suit		MATRIMONIAL SUIT		1	0	0	0	2025	Y	Petitioner	Respondent			0	0	1008037	N	\N	0	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
60	PROB.CASE		PROB.CASE		1	0	0	0	2025	Y	Petitioner	Respondent			0	0	1005037	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
65	A.C.(D.B.)		APPEAL CASE (DIVISION BENCH)		1	0	0	0	2025	Y	Petitioner	Respondent			0	0	1004005	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
66	A.C.(S.B.)		APPEAL CASE (SINGLE BENCH)		1	0	0	0	2025	Y	Petitioner	Respondent			0	0	1004031	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
20	C.A.	\N	COMPANY APPEAL	\N	1	0	2017	0	2025	Y	Petitioner 	Respondent 	\N	\N	0	0	1004007	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
7	Cr.Rev.		CRIMINAL REVISION		2	0	0	12	2025	Y	Petitioner	Respondent			0	0	1006031	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-06 12:53:47.766271	\N	N	JHHC01
8	C.R.		CIVIL REVISION		1	0	0	0	2025	Y	Petitioner	Respondent			0	0	1006031	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
51	W.P.(Cr.)		CRIMINAL WRIT PETITIONS		2	0	0	15	2025	Y	Petitioner	Respondent			0	0	1001031	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-04 15:37:05.499793	\N	N	JHHC01
53	Cr.M.P.		CRIMINAL MISCELLENOUS PETITION		2	1	2022	29	2025	Y	Petitioner	Respondent			0	0	1005025	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-06 13:13:30.533467	\N	N	JHHC01
57	A.B.A.		ANTICIPATORY BAIL		2	1	2023	48	2025	Y	Petitioner	Respondent			0	0	1010006	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-06 16:48:45.187637	\N	N	JHHC01
67	Acq. App.		ACQUITTAL APPEAL		2	0	0	4	2025	Y	Petitioner	Respondent			0	0	1004001	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-04 16:00:46.180632	\N	N	JHHC01
24	Cr.A(SJ)		CRIMINAL APPEAL (SINGLE JUDGE)		2	1	2023	14	2025	Y	Petitioner	Respondent			0	0	1004031	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-06 11:43:10.279147	\N	N	JHHC01
11	C.Rev.		CIVIL REVIEW		1	0	0	2	2025	Y	Petitioner	Respondent			0	0	1009031	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 16:00:22.278767	\N	N	JHHC01
1	FA		FIRST APPEAL		1	1	2022	8	2025	Y	Petitioner	Respondent			0	0	1003031	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-06 14:41:50.601118	\N	N	JHHC01
9	SA		SECOND APPEAL		1	1	2022	0	2025	Y	Petitioner	Respondent			0	0	1002031	N	\N	2	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-02 10:32:36.165611	\N	N	JHHC01
55	Cont.(Cvl)		CIVIL CONTEMPT		1	3	2023	42	2025	Y	Petitioner	Respondent			0	0	1005008	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-06 14:54:08.539442	\N	N	JHHC01
2	MA		MISCELLENOUS APPEAL		1	0	0	5	2025	Y	Petitioner	Respondent			0	0	1004025	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-04 12:04:13.525751	\N	N	JHHC01
5	Cr.A(DB)		CRIMINAL APPEAL (DIVISION BENCH)		2	1	2022	12	2025	Y	Petitioner	Respondent			0	0	1004031	N	\N	2	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-06 11:36:39.698964	\N	N	JHHC01
58	C.M.P.		CIVIL MISCELLENOUS PETITION		1	1	2022	17	2025	Y	Petitioner	Respondent			0	0	1005025	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-06 15:16:59.260124	\N	N	JHHC01
50	WPC		WRIT PETITIONS		1	1	2023	40	2025	Y	Petitioner	Respondent			0	0	1001031	N	\N	1	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-06 16:43:10.524916	\N	N	JHHC01
56	B.A.		REGULAR BAIL		2	1	2020	139	2025	Y	Petitioner	Respondent			0	0	1010006	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-06 16:37:11.220522	\N	N	JHHC01
63	Tr.Pet.CVL		CIVIL TRANSFER PETITION		1	1	2022	2	2025	Y	Petitioner	Respondent			0	0	1010025	N	\N	3	0	2025	N	0	2025	0	0	0	0	\N	U	2025-01-06 14:56:26.705471	\N	N	JHHC01
\.


--
-- Data for Name: high_court_transaction_number_tracker; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.high_court_transaction_number_tracker (date_key, counter) FROM stdin;
2025-03-07	21
2025-03-10	4
2025-03-11	16
2025-03-12	23
2025-03-18	7
2025-03-19	1
2025-03-20	1
2025-03-25	4
2025-04-25	1
2025-05-14	4
2025-05-19	271
2025-05-20	3
2025-05-22	5
2025-05-16	30
2025-05-23	3
2025-06-02	3
2025-06-03	1
2025-06-04	2
2025-07-10	18
2025-06-26	32
2025-07-01	2
2025-07-03	1
2025-07-07	1
2025-07-11	3
2025-06-13	14
2025-07-08	5
2025-06-05	17
2025-06-06	9
2025-06-16	12
2025-07-14	10
2025-06-17	4
2025-07-15	2
2025-06-18	4
2025-07-16	1
2025-07-17	2
2025-06-09	19
2025-06-10	3
2025-06-11	2
2025-06-19	12
2025-06-12	6
2025-06-20	11
2025-07-09	39
2025-06-23	14
2025-06-24	11
2025-06-25	1
\.


--
-- Data for Name: high_court_users_master; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.high_court_users_master (id, user_name, name, email, mobile, password, role_id) FROM stdin;
\.


--
-- Data for Name: highcourt_applicant_document_detail; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.highcourt_applicant_document_detail (id, application_number, document_type, number_of_page, amount, file_name, upload_status, uploaded_by, uploaded_date, certified_copy_file_name, certified_copy_upload_status, certified_copy_uploaded_by, certified_copy_uploaded_date, created_at) FROM stdin;
8	HC05723052500	aadhar card	2	10.00	HC05723052500_1747986933.pdf	t	3	2025-05-23 13:25:33	HC05723052500_1748341123.pdf	t	3	2025-05-27 15:48:43	2025-05-23 13:25:33
9	HC03930052504	aadhar card	2	10.00	HC03930052504_1748604628.pdf	t	3	2025-05-30 17:00:28	HC03930052504_1748604737.pdf	t	3	2025-05-30 17:02:18	2025-05-30 17:00:28
10	HC01903062500	aadhar card	3	15.00	HC01903062500_1748934221.pdf	t	3	2025-06-03 12:33:41	\N	f	\N	\N	2025-06-03 12:33:41
11	HC05716062500	order copy	107	535.00	HC05716062500_1750060396.pdf	t	5	2025-06-16 13:23:16	\N	f	\N	\N	2025-06-16 13:23:16
12	HC05716062500	bail copy	2	10.00	HC05716062500_1750060638.pdf	t	5	2025-06-16 13:27:18	\N	f	\N	\N	2025-06-16 13:27:18
13	HC06510062500	decreement copy	2	10.00	HC06510062500_1750073012.pdf	t	4	2025-06-16 16:53:32	\N	f	\N	\N	2025-06-16 16:53:32
14	HC06117062500	aadhar card	1	5.00	HC06117062500_1750140066.pdf	t	3	2025-06-17 11:31:06	\N	f	\N	\N	2025-06-17 11:31:06
15	HC05723062500	aadhar card	3	15.00	HC05723062500_1750656302.pdf	t	3	2025-06-23 10:55:02	\N	f	\N	\N	2025-06-23 10:55:02
17	HC05726062500	order copy	1	5.00	HC05726062500_1750925756.pdf	t	3	2025-06-26 13:45:56	\N	f	\N	\N	2025-06-26 13:45:56
16	HC05725062500	aadhar card	3	15.00	HC05725062500_1750829041.pdf	t	3	2025-06-25 10:54:01	HC05725062500_1750932230.pdf	t	3	2025-06-26 15:33:50	2025-06-25 10:54:01
18	HC06601072500	aadhar card	1	5.00	HC06601072500_1751348474.pdf	t	3	2025-07-01 11:11:14	\N	f	\N	\N	2025-07-01 11:11:14
19	HC05708072501	aadhar card	3	15.00	HC05708072501_1751961117.pdf	t	3	2025-07-08 13:21:57	HC05708072501_1751961239.pdf	t	3	2025-07-08 13:23:59	2025-07-08 13:21:57
\.


--
-- Data for Name: jegras_merchant_details_dc; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jegras_merchant_details_dc (id, dist_code, deptid, recieptheadcode, treascode, ifmsofficecode, securitycode) FROM stdin;
2	347	JHECOURTCERTI	003001101010101	DRN	DRNHCR001	sec1234
3	350	JHECOURTCERTI	003001101010101	DRN	DRNHCR001	sec1234
4	354	JHECOURTCERTI	003001101010101	DRN	DRNHCR001	sec1234
5	362	JHECOURTCERTI	003001101010101	DRN	DRNHCR001	sec1234
6	346	JHECOURTCERTI	003001101010101	DRN	DRNHCR001	sec1234
7	349	JHECOURTCERTI	003001101010101	DRN	DRNHCR001	sec1234
8	351	JHECOURTCERTI	003001101010101	DRN	DRNHCR001	sec1234
9	366	JHECOURTCERTI	003001101010101	DRN	DRNHCR001	sec1234
10	360	JHECOURTCERTI	003001101010101	DRN	DRNHCR001	sec1234
11	363	JHECOURTCERTI	003001101010101	DRN	DRNHCR001	sec1234
12	365	JHECOURTCERTI	003001101010101	DRN	DRNHCR001	sec1234
13	348	JHECOURTCERTI	003001101010101	DRN	DRNHCR001	sec1234
14	359	JHECOURTCERTI	003001101010101	DRN	DRNHCR001	sec1234
15	356	JHECOURTCERTI	003001101010101	DRN	DRNHCR001	sec1234
16	353	JHECOURTCERTI	003001101010101	DRN	DRNHCR001	sec1234
17	358	JHECOURTCERTI	003001101010101	DRN	DRNHCR001	sec1234
18	368	JHECOURTCERTI	003001101010101	DRN	DRNHCR001	sec1234
19	357	JHECOURTCERTI	003001101010101	DRN	DRNHCR001	sec1234
21	364	JHECOURTCERTI	003001101010101	DRN	DRNHCR001	sec1234
22	352	JHECOURTCERTI	003001101010101	DRN	DRNHCR001	sec1234
23	369	JHECOURTCERTI	003001101010101	DRN	DRNHCR001	sec1234
24	367	JHECOURTCERTI	003001101010101	DRN	DRNHCR001	sec1234
20	361	JHECOURTCERTI	003001101010101	TRN	DRNHCR001	sec1234
1	355	JHECOURTCERTI	003001101010104	TRN	Dra	sec1234
\.


--
-- Data for Name: jegras_merchant_details_hc; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jegras_merchant_details_hc (id, deptid, recieptheadcode, treascode, ifmsofficecode, securitycode) FROM stdin;
1	JHECOURTCERTI	003001101010101	DRN	DRNHCR001	sec1234
\.


--
-- Data for Name: jegras_payment_request_dc; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jegras_payment_request_dc (deptid, application_number, recieptheadcode, depositername, depttranid, amount, depositerid, panno, addinfo1, addinfo2, addinfo3, treascode, ifmsofficecode, securitycode, response_url, created_at, updated_at, establishment_code, district_code) FROM stdin;
JHECOURTCERTI	RANW19314072501	003001101010101	TUSHAR ANAND	TRN1407202503	70.00	DRID001	N/A	RANW19314072501	normal	N/A	DRN	DRNHCR001	sec1234	http://10.134.8.12/api/occ/gras_resp_cc	2025-07-14 13:36:57.734157	2025-07-14 13:36:57.734157	JHRN01	364
JHECOURTCERTI	RANW19314072502	003001101010101	TESTING	TRN1407202504	25.00	DRID001	N/A	RANW19314072502	normal	N/A	DRN	DRNHCR001	sec1234	http://10.134.8.12/api/occ/gras_resp_cc	2025-07-14 15:26:53.360626	2025-07-14 15:26:53.360626	JHRN01	364
JHECOURTCERTI	RANW19314072502	003001101010101	TESTING	TRN1407202505	45.00	DRID001	N/A	RANW19314072502	deficit	N/A	DRN	DRNHCR001	sec1234	http://10.134.8.12/api/occ/gras_resp_cc	2025-07-14 15:58:12.273067	2025-07-14 15:58:12.273067	JHRN01	364
\.


--
-- Data for Name: jegras_payment_request_hc; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jegras_payment_request_hc (deptid, application_number, recieptheadcode, depositername, depttranid, amount, depositerid, panno, addinfo1, addinfo2, addinfo3, treascode, ifmsofficecode, securitycode, response_url, created_at, updated_at) FROM stdin;
JHECOURTCERTI	HCW05714072501	003001101010101	TUSHAR ANAND	TRN1407202502	20.00	DRID001	N/A	HCW05714072501	normal	N/A	DRN	DRNHCR001	sec1234	http://10.134.8.12/api/occ/gras_resp_cc	2025-07-14 12:17:09.526988	2025-07-14 12:17:09.526988
JHECOURTCERTI	HCW00514072501	003001101010101	SALIM ANSARI	TRN1407202506	20.00	DRID001	N/A	HCW00514072501	normal	N/A	DRN	DRNHCR001	sec1234	http://10.134.8.12/api/occ/gras_resp_cc	2025-07-14 16:31:58.29736	2025-07-14 16:31:58.29736
JHECOURTCERTI	HCW00514072501	003001101010101	SALIM ANSARI	TRN1407202507	45.00	DRID001	N/A	HCW00514072501	deficit	N/A	DRN	DRNHCR001	sec1234	http://10.134.8.12/api/occ/gras_resp_cc	2025-07-14 16:34:59.396096	2025-07-14 16:34:59.396096
JHECOURTCERTI	HCW05714072501	003001101010101	TUSHAR ANAND	TRN1407202508	15.00	DRID001	N/A	HCW05714072501	normal	N/A	DRN	DRNHCR001	sec1234	http://10.134.8.12/api/occ/gras_resp_cc	2025-07-14 16:41:31.397479	2025-07-14 16:41:31.397479
JHECOURTCERTI	HCW00514072501	003001101010101	BIPUL MISHRA	TRN1407202509	10.00	DRID001	N/A	HCW00514072501	normal	N/A	DRN	DRNHCR001	sec1234	http://10.134.8.12/api/occ/gras_resp_cc	2025-07-14 16:46:22.6767	2025-07-14 16:46:22.6767
JHECOURTCERTI	HCW00314072501	003001101010101	BSSR PRESENT SECRETARY SUMEET GUPTA	TRN1407202510	15.00	DRID001	N/A	HCW00314072501	normal	N/A	DRN	DRNHCR001	sec1234	http://10.134.8.12/api/occ/gras_resp_cc	2025-07-14 17:46:31.072693	2025-07-14 17:46:31.072693
JHECOURTCERTI	HCW05715072501	003001101010101	TUSHAR ANAND	TRN1507202501	25.00	DRID001	N/A	HCW05715072501	normal	N/A	DRN	DRNHCR001	sec1234	http://10.134.8.12/api/occ/gras_resp_cc	2025-07-15 16:56:33.246243	2025-07-15 16:56:33.246243
JHECOURTCERTI	HCW05715072502	003001101010101	TEST	TRN1507202502	20.00	DRID001	N/A	HCW05715072502	normal	N/A	DRN	DRNHCR001	sec1234	http://10.134.8.12/api/occ/gras_resp_cc	2025-07-15 17:52:03.891761	2025-07-15 17:52:03.891761
JHECOURTCERTI	HCW05715072502	003001101010101	TEST	TRN1707202501	45.00	DRID001	N/A	HCW05715072502	deficit	N/A	DRN	DRNHCR001	sec1234	http://10.134.8.12/api/occ/gras_resp_cc	2025-07-17 11:07:55.615005	2025-07-17 11:07:55.615005
JHECOURTCERTI	HCW05717072501	003001101010101	TUSHAR ANAND	TRN1707202502	15.00	DRID001	N/A	HCW05717072501	normal	N/A	DRN	DRNHCR001	sec1234	http://10.134.8.12/api/occ/gras_resp_cc	2025-07-17 11:09:05.044642	2025-07-17 11:09:05.044642
\.


--
-- Data for Name: jegras_payment_response_dc; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jegras_payment_response_dc (deptid, application_number, recieptheadcode, depositername, depttranid, amount, depositerid, panno, addinfo1, addinfo2, addinfo3, treascode, ifmsofficecode, status, paymentstatusmessage, grn, cin, ref_no, txn_date, txn_amount, challan_url, pmode, addinfo4, addinfo5) FROM stdin;
JHECOURTCERTI	RANW19314072501	003001101010101	DR SURJEET SINGH	TR176261120073123	15.00	DR000176	NA	RANW19314072501	NA	NA	DRN	DRNHCR001	SUCCESS	Y	2002923447	10002162020112612021	9121604485016	2020-11-26	15.00	https://finance.jharkhand.gov.in/jegras/frmdownloadchallan.aspx?PDetails=8DjYDNi2nRyk9wHjeq1+Zz+Cmu+tZPmERu9SAhZhMi4=	PAYMENT GATEWAY	\N	NA
\.


--
-- Data for Name: jegras_payment_response_hc; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jegras_payment_response_hc (deptid, application_number, recieptheadcode, depositername, depttranid, amount, depositerid, panno, addinfo1, addinfo2, addinfo3, treascode, ifmsofficecode, status, paymentstatusmessage, grn, cin, ref_no, txn_date, txn_amount, challan_url, pmode, addinfo4, addinfo5) FROM stdin;
JHECOURTCERTI	HCW05714072501	003001101010101	DR SURJEET SINGH	TR176261120073123	15.00	DR000176	NA	HCW05714072501	NA	NA	DRN	DRNHCR001	SUCCESS	Y	2002923447	10002162020112612021	9121604485016	2020-11-26	15.00	https://finance.jharkhand.gov.in/jegras/frmdownloadchallan.aspx?PDetails=27wipX6dw7NqW4Z5YgLTdMM+10Po120uR/5VGDz+hAw=	PAYMENT GATEWAY	\N	NA
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: log_activity_dc; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.log_activity_dc (id, log_desc, log_date, log_action, user_id, dist_code, est_code, username) FROM stdin;
1	Submenu Name Changed	2025-06-18 16:20:46	update	6	364	\N	Tushar Anand
2	Submenu Name Changed	2025-06-18 16:26:25	update	6	364	JHRN01	Tushar Anand
3	Document uploaded for application no: RANW19318062501	2025-06-18 16:30:59	update	6	364	JHRN01	Tushar Anand
5	Document uploaded for application no: RANW19318062501	2025-06-18 16:39:50	update	6	364	JHRN01	Tushar Anand
6	Reject Application no: RAN08120062506	2025-06-24 17:56:58	Reject	8	364	JHRN01	tushar_1
7	Upload Document For Application no: RAN16526062502	2025-06-26 12:06:22	Upload Document	8	364	JHRN03	tushar_1
8	Upload Document For Application no: RAN22726062503	2025-06-26 12:12:46	Upload Document	8	364	JHRN01	tushar_1
9	Upload Document For Application no: RAN21926062501	2025-06-26 12:14:26	Upload Document	8	364	JHRN01	tushar_1
10	Upload Document For Application no: RAN08120062504	2025-06-26 13:29:26	Upload Document	8	364	JHRN01	tushar_1
11	Upload Document For Application no: RAN09820062503	2025-06-26 13:42:59	Upload Document	8	364	JHRN01	tushar_1
12	Pyment Notification Send For Application no: RAN09820062503	2025-06-26 13:43:06	Send Notification	8	364	JHRN01	tushar_1
13	Reject Application no: RAN22714072501	2025-07-14 13:41:49	Reject	8	364	JHRN01	tushar_1
14	Document uploaded for application no: RANW19314072501	2025-07-14 13:47:19	Upload Certified Copy	8	364	JHRN01	tushar_1
15	Document uploaded for application no: RANW19314072501	2025-07-14 13:47:25	Upload Certified Copy	8	364	JHRN01	tushar_1
16	Document uploaded for application no: RANW19314072501	2025-07-14 13:47:37	Upload Certified Copy	8	364	JHRN01	tushar_1
17	Document uploaded for application no: RANW19314072501	2025-07-14 13:47:43	Upload Certified Copy	8	364	JHRN01	tushar_1
18	Document uploaded for application no: RANW19314072501	2025-07-14 13:48:08	Upload Certified Copy	8	364	JHRN01	tushar_1
19	Cerified Copy ready notification send for application no: RANW19314072501	2025-07-14 13:48:12	notification send	8	364	JHRN01	tushar_1
20	Document uploaded for application no: RANW19314072502	2025-07-14 15:30:21	Upload Certified Copy	8	364	JHRN01	tushar_1
21	Document uploaded for application no: RANW19314072502	2025-07-14 15:30:26	Upload Certified Copy	8	364	JHRN01	tushar_1
22	Document uploaded for application no: RANW19314072502	2025-07-14 15:57:32	Upload Certified Copy	8	364	JHRN01	tushar_1
23	Deficit notification send for application no: RANW19314072502	2025-07-14 15:57:36	notification send	8	364	JHRN01	tushar_1
24	Cerified Copy ready notification send for application no: RANW19314072502	2025-07-14 16:00:31	notification send	8	364	JHRN01	tushar_1
25	Upload Document For Application no: RAN22816072501	2025-07-16 18:01:18	Upload Document	8	364	JHRN01	tushar_1
\.


--
-- Data for Name: log_activity_hc; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.log_activity_hc (id, log_desc, log_date, log_action, user_id, username) FROM stdin;
2	Submenu Name Changed	2025-06-18 15:33:24	update	3	Tushar Anand
3	Submenu Name Changed	2025-06-18 16:50:02	update	3	Tushar Anand
4	Submenu Name inserted	2025-06-23 16:53:45	insert	3	Tushar Anand
5	Submenu Name inserted	2025-06-23 16:59:14	insert	3	Tushar Anand
6	Submenu Name inserted	2025-06-23 17:08:26	insert	3	Tushar Anand
1	Submenu Changed	2025-06-17 15:21:50	update	3	Tushar Anand
7	Upload Document For Application no: HC05725062500	2025-06-25 10:54:01	Upload Document	3	Tushar Anand
8	Pyment Notification Send For Application no: HC05725062500	2025-06-25 10:54:04	Send Notification	3	Tushar Anand
9	Document uploaded for application no: HCW05724062505	2025-06-25 12:22:01	Upload Certified Copy	3	Tushar Anand
10	Document uploaded for application no: HCW05724062505	2025-06-25 12:22:06	Upload Certified Copy	3	Tushar Anand
11	Document uploaded for application no: HCW05724062505	2025-06-25 12:22:10	Upload Certified Copy	3	Tushar Anand
12	Deficit notification send for application no: HCW05724062505	2025-06-25 12:22:13	notification send	3	Tushar Anand
13	Document uploaded for application no: HCW05724062504	2025-06-25 12:25:40	Upload Certified Copy	3	Tushar Anand
14	Document uploaded for application no: HCW05724062504	2025-06-25 12:25:44	Upload Certified Copy	3	Tushar Anand
15	Document uploaded for application no: HCW05724062504	2025-06-25 12:25:49	Upload Certified Copy	3	Tushar Anand
16	Cerified Copy ready notification send for application no: HCW05724062504	2025-06-25 12:25:53	notification send for certified copy	3	Tushar Anand
17	Cerified Copy ready notification send for application no: HCW05724062505	2025-06-25 12:26:14	notification send for certified copy	3	Tushar Anand
18	Upload Document For Application no: HC05726062500	2025-06-26 13:45:56	Upload Document	3	Tushar Anand
19	Pyment Notification Send For Application no: HC05726062500	2025-06-26 13:46:05	Send Notification	3	Tushar Anand
20	Certified copy Notification Send For Application no: HC05725062500	2025-06-26 15:34:25	Cerified Copy Notification	3	Tushar Anand
21	Upload Document For Application no: HC06601072500	2025-07-01 11:11:14	Upload Document	3	Tushar Anand
22	Pyment Notification Send For Application no: HC06601072500	2025-07-01 11:11:17	Send Notification	3	Tushar Anand
23	Submenu Name inserted	2025-07-01 17:25:09	insert	3	Tushar Anand
24	Upload Document For Application no: HC05708072501	2025-07-08 13:21:57	Upload Document	3	Tushar Anand
25	Pyment Notification Send For Application no: HC05708072501	2025-07-08 13:22:01	Send Notification	3	Tushar Anand
26	Certified copy Notification Send For Application no: HC05708072501	2025-07-08 13:24:03	Cerified Copy Notification	3	Tushar Anand
27	Document uploaded for application no: HCW05708072501	2025-07-08 13:25:58	Upload Certified Copy	3	Tushar Anand
28	Document uploaded for application no: HCW05708072501	2025-07-08 13:26:03	Upload Certified Copy	3	Tushar Anand
29	Document uploaded for application no: HCW05708072501	2025-07-08 13:26:08	Upload Certified Copy	3	Tushar Anand
30	Document uploaded for application no: HCW05708072501	2025-07-08 13:26:27	Upload Certified Copy	3	Tushar Anand
31	Document uploaded for application no: HCW05708072501	2025-07-08 13:26:50	Upload Certified Copy	3	Tushar Anand
32	Document uploaded for application no: HCW05708072501	2025-07-08 13:27:01	Upload Certified Copy	3	Tushar Anand
33	Document uploaded for application no: HCW05708072501	2025-07-08 13:27:12	Upload Certified Copy	3	Tushar Anand
34	Document uploaded for application no: HCW05708072501	2025-07-08 13:27:39	Upload Certified Copy	3	Tushar Anand
35	Cerified Copy ready notification send for application no: HCW05708072501	2025-07-08 13:27:42	notification send for certified copy	3	Tushar Anand
36	Document uploaded for application no: HCW05708072502	2025-07-08 17:58:45	Upload Certified Copy	4	Rahul Thakur
37	Document uploaded for application no: HCW05708072502	2025-07-08 17:59:15	Upload Certified Copy	4	Rahul Thakur
38	Deficit notification send for application no: HCW05708072502	2025-07-08 18:00:33	notification send	4	Rahul Thakur
39	Document uploaded for application no: HCW05009072502	2025-07-09 11:21:15	Upload Certified Copy	4	Rahul Thakur
40	Deficit notification send for application no: HCW05009072502	2025-07-09 11:21:40	notification send	4	Rahul Thakur
41	Document uploaded for application no: HCW00514072501	2025-07-14 16:33:33	Upload Certified Copy	3	Tushar Anand
42	Document uploaded for application no: HCW00514072501	2025-07-14 16:33:38	Upload Certified Copy	3	Tushar Anand
43	Document uploaded for application no: HCW00514072501	2025-07-14 16:34:07	Upload Certified Copy	3	Tushar Anand
44	Deficit notification send for application no: HCW00514072501	2025-07-14 16:34:47	notification send	3	Tushar Anand
45	Cerified Copy ready notification send for application no: HCW00514072501	2025-07-14 16:36:23	notification send for certified copy	3	Tushar Anand
46	Document uploaded for application no: HCW00514072501	2025-07-14 16:58:27	Upload Certified Copy	3	Tushar Anand
47	Cerified Copy ready notification send for application no: HCW00514072501	2025-07-14 16:58:32	notification send for certified copy	3	Tushar Anand
48	Document uploaded for application no: HCW00314072501	2025-07-14 17:48:22	Upload Certified Copy	3	Tushar Anand
49	Document uploaded for application no: HCW00314072501	2025-07-14 17:48:40	Upload Certified Copy	3	Tushar Anand
50	Cerified Copy ready notification send for application no: HCW00314072501	2025-07-14 17:48:54	notification send for certified copy	3	Tushar Anand
51	Document uploaded for application no: HCW05715072501	2025-07-16 15:48:19	Upload Certified Copy	3	Tushar Anand
52	Document uploaded for application no: HCW05715072501	2025-07-17 10:53:53	Upload Certified Copy	3	Tushar Anand
53	Document uploaded for application no: HCW05715072501	2025-07-17 10:54:00	Upload Certified Copy	3	Tushar Anand
54	Document uploaded for application no: HCW05715072501	2025-07-17 10:54:18	Upload Certified Copy	3	Tushar Anand
55	Document uploaded for application no: HCW05715072501	2025-07-17 10:54:23	Upload Certified Copy	3	Tushar Anand
56	Document uploaded for application no: HCW05715072501	2025-07-17 10:57:35	Upload Certified Copy	3	Tushar Anand
57	Document uploaded for application no: HCW05715072501	2025-07-17 10:57:40	Upload Certified Copy	3	Tushar Anand
58	Deficit notification send for application no: HCW05715072501	2025-07-17 10:57:44	notification send	3	Tushar Anand
59	Document uploaded for application no: HCW05715072502	2025-07-17 11:07:00	Upload Certified Copy	3	Tushar Anand
60	Document uploaded for application no: HCW05715072502	2025-07-17 11:07:06	Upload Certified Copy	3	Tushar Anand
61	Document uploaded for application no: HCW05715072502	2025-07-17 11:07:29	Upload Certified Copy	3	Tushar Anand
62	Deficit notification send for application no: HCW05715072502	2025-07-17 11:07:32	notification send	3	Tushar Anand
63	Document uploaded for application no: HCW05717072501	2025-07-17 11:11:47	Upload Certified Copy	3	Tushar Anand
64	Document uploaded for application no: HCW05717072501	2025-07-17 11:12:01	Upload Certified Copy	3	Tushar Anand
65	Cerified Copy ready notification send for application no: HCW05717072501	2025-07-17 11:12:06	notification send for certified copy	3	Tushar Anand
66	Submenu Name inserted	2025-07-17 11:14:49	insert	3	Tushar Anand
67	Submenu Name Changed	2025-07-17 11:15:41	update	3	Tushar Anand
\.


--
-- Data for Name: menu_master; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.menu_master (menu_id, menu_name, menu_icon) FROM stdin;
2	Master Data	bi bi-box-seam-fill
1	Dashboard	bi bi-speedometer
3	User and Permission	bi bi-person-circle
11	Report HC	bi bi-flag-fill
12	Report DC	bi bi-flag-fill
5	Others Copy	bi bi-columns
8	Order And Judgement	bi bi-bank
6	Other's Copy	bi bi-pc-display-horizontal
4	Order & Judgement	bi bi-bank
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: role_master; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.role_master (role_id, role_name) FROM stdin;
5	Hc Verifier
4	Hc Admin
6	Dc Admin
7	Dc Verifier
1	Supuser
\.


--
-- Data for Name: role_permissions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.role_permissions (id, role_id, menu_id, submenu_id) FROM stdin;
623	7	6	17
413	5	4	12
414	5	4	29
624	7	6	19
625	7	6	20
626	7	6	36
627	7	6	37
628	7	8	21
629	7	8	33
415	5	4	30
416	5	5	14
417	5	5	15
418	5	5	16
419	5	5	31
420	5	5	32
630	7	8	34
631	7	12	42
632	7	12	43
633	7	12	44
634	7	12	45
635	1	2	3
636	1	2	4
637	1	2	5
638	1	2	6
639	1	2	46
640	1	3	8
641	1	3	9
642	1	3	10
643	1	4	12
644	1	4	29
645	1	4	30
646	1	5	14
647	1	5	15
648	1	5	16
649	1	5	31
650	1	5	32
651	1	11	38
652	1	11	39
653	1	11	40
654	1	11	41
582	4	2	5
583	4	3	9
584	4	4	12
585	4	4	29
586	4	4	30
587	4	5	14
588	4	5	15
589	4	5	16
590	4	5	31
591	4	5	32
592	4	11	38
593	4	11	39
594	4	11	40
595	4	11	41
609	6	2	6
610	6	3	10
611	6	6	17
612	6	6	19
613	6	6	20
614	6	6	36
615	6	6	37
616	6	8	21
617	6	8	33
618	6	8	34
619	6	12	42
620	6	12	43
621	6	12	44
622	6	12	45
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
POMCA1kEK8tZ73v63wPu9EQhAUdmrgNy6WFXDFpb	\N	10.134.8.12	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36	ZXlKcGRpSTZJbHBZYzBWMmJGaDBjMUJYVG1kMFdqZFBXamRRU1VFOVBTSXNJblpoYkhWbElqb2lVMk13VWxndlpTOW1LM0k0UTFVNFpHTjJOR3AzTVVvelRGSmlZMWhIVlhOeVlrb3dRelpsT0RCUlZGaHpTSFpxUVd0dlVEZFVNR3B2UlZaQmNsTmtha0p6TmsxaVFtRklTaTlQZVVSbVZXZG5SamhMYmpkRVRURTVPRlo1UkZSWFMzUjBTVlV2VGt4elRqUmpPVVZTZHpCYVIxUnNablZZYWxZd2R6VlZNRkZLTlZwcVRFNTZMekpNTW0xdlpESjVMM3BETmtkV1ZEQlBRakJ4U0dOb1VqRktRVTQxVGtWUE9WWlhabGgyUTFrNVZtMXlSM1JhWWxwSmJVVkxlbFJuYkVOWWJuVlNhbVZxVERaSU5sbGpkRm9yY0dOWmVVOU5Oa3gzS3paRmVHd3diMUZ0ZUVWMVkxaHdkM2RFVDFKa1NVNHdNMnBwUm5kb04yOUNhMkZXY1RSMGFHRXdUWFJ2UzJaTVJUUTBVRU12VlVONU1rRnRjVUYzUTFOWFlXVkhXVWxCWVVaemJYZFBOR1F2ZUhob2F5OTJXVXBNT1hwMFdYRjZORFJ0ZG1sdWMwSkhlVWhTS3psSFdtcFBhbmR4U0hsT1dVWkZSbmhZUkhoVVJqRm1kRzFLUkZsQmRHSkpPRU5vVDNKYWNITjBha3hwY2taWUt6VjRSbFJKVWxWbVFuWlBORmQzZEc0eE9VZFhZVFZOYVRsRFlXSTVOblJDTWpkQmVEaFViamxGUVdSTlpsRXpUMGhKYUhkWE0xSnNaRlYzVHpGaFR6TkJhbmRaTW1Kc1Iyc3dOM041UTNJMEsxRjNWVXAwVUVwSlRYWmlZekJOZUd4RWFUSlNMelZPUmtWS1dIWmhlVlJzZW1sT1JVeGlaVmROUTJGbVdFeFFkbWRoWnpsclptTm5MMjFQWW5kbmJHVm5SWFJDVVZFeVlrRjRVamhwV0RabFRFcENOMmhtUjNJM1kxTnFTWHBuWlZselRIZExlVE5RYzJ0aFZYbHVMMnMxYWxWWGRERlBhWGQwYkhab1pVUnBjRWxXUkhNeWJqWnVSVEYyVTBKMGRqUndjbFZYWVVkNmNEUmtkbVZqYld0SFMzcDVjRUppVnpWMFprZE5lVmhXVkRSM2JHaFVUSGR5VW0xNFFsRlVhRlZhYkZBMFdURmhUMlp2Vnpac1NEQkZlVlprVkVkWlVsRmFVR3RpTmxWVFNYbExMMFF5TTFOMmIyOUVRVkpJUzBWQldqRTNkekJtTTI1aE0zVnVjbXgzVURKNlprMUJhR3AxVFdWYWFuWlFOa05VUVdwQ1IyNWFWeTloYmtzMFprTmlSalEzVlZodVFWaHdZMVZNUVZSTE5YWkZaVGRKYVVoV2RYRnpUMlp4TTNwaU9WSkNaVU5aYUM5dFMySnZNMFJzTUVocVRYaExOMU5LYmtGR1VHMUtiM2hKYkZaRWJIZ3lPVWRPWkRaWVNtbHlRa1pIZUV0c05VY3hVV1ZDYUV4a016QkpjbVJXUTJaSU56TnlPVTF2V1ZGVWRVWXplV3BzYVZGalJWWnZTRGRDVUVwdmFURkpZMnRHV0VKWVdrTXJXVmxzUW1nd00ycEhhVGM1U1VkNE1ubEZTMjFGUnpKcGMwZ3hlakJ2Vm14blpHWnNURzgyUkZZeVZrMURPR2t6YVhsSVdHSlFWMlV4YjNWVU1pczFjeTh4U1RKS2JIazRRbGxKUlRZNGRUQkxWVkZCV1VkQ2JGUlRTRlphWlN0WFowWkdjMDA1TmtKWFEybG9ZVm8yUXpobmVFZDBNbTlDTTNFd1RucEZNblYxVkVWT2MxQXpNMEZHUW5wQ1puVjFWRkZVTkhOdlFVSlBWSGRHVG1adFJqQmlialJpTnpaMk5VaFFXRVo1V1VKRlVGVnhTMDgwVXl0WWJXRnpSMGQ2WTNreE1IZDFkVkprVUZrdmFFZDZWRVY2ZEdaS1RuWndhR2RJVEhKS2JWaGlVMkk0ZDBOM1ZVTmxZVGhsVEZGSFZFWkZjM3ByY0dSNVpqbGlTRTEwUm5KMVJUZG1jbGdyWVcxWVNrVndWazlqYW5oSmMydHRiemxwV2xkeFNrNHhObUpZZUVkTFowUkdielZLTjNCUlVsZElRbkJhUWtkeWNteHlZVmx2UWpCMFQzZHJRVlp1TmpkRFRsVnJkRWxZYW1abEwwOU5aVUk0TUZaWGVXaG5XamR1YkhSSmVHZFNNRVpFUjI5dVdWSlhkalJxVEN0SmNHTjRSVUZDUWtaS1FuUkxWbGhvZG1ocWNWRlljMm8wTTI0d1RGRnVkbE50TTFSNVpVWXZLMWgyZG5oaWQwaERUa1pFYUdsamJta3diM3BQVVhKTFNVeE5ZVWhCZWpOd1QwZHhSa1pXU0ZOcVIwSk1Obmt2VVZWbFduSmlkMk5IUW1adVZHNTFUU3MxY0RkalRETnpkblZRVVcxeVNTdGFOMDFwSzNGeGExQmtkMWcwYkROSFJDOXRUVWhLU0RCQlRHTlNVMjVRTTNkRU9GZzJjVWNyYkdGV2JrbG5OMnRzVEVNeFVrUTJNMHh0VW5oemFtOHJWM2h6Ykd0VWRrbzFVbTFWTkRCUFQzSk9UeTgxZG1ZemRWcExla1ZQVnpSTFoycFVOMGd2UzJKUVVHVnBUbXBvYmtwSlpHZGFkSGxrTmpadGEybFNhRVZEVlhsNlJUVkVRbTAwTlhST1lVNVlaRlZqYTFFeVQzazJkbWhYVlZWRmNEZFhWbU5KWlZkak5VWkxVbGM1VFV4Skx6RXdVVlpKWTBGaVRETnhkalZCZVhrek1GUkNla0UwZFVGSmRWUlVUazUxWlVSMVpsZHJNREozZEc5ak5WcFZZazQ1THpoMloxWklUa0ZTVG10WFIyOXFlV1JQWjNCaUwxRnFVR2hXTnpSdlZXNVNNVTR5UnpaaFozaHlObVZQYVVWUlpuWjJla3Q1ZFZKMWVrUllhMDFGTVc1aFFrWnlOSE5GU2xBclR6ZEhhR05uWlZwblRtaGpabUZTTUUxeGNXaERLMDlxWlVscll6STFWMjk0VFZCME5VVm9JaXdpYldGaklqb2laV1l3WkdaaE5URTFOekZpTWpjMU1EYzVaRGM0TmpNMFpUWTRaalJrTmpZeFlUTm1NVEU0TlRObVlqWXpPREk0TW1abE9UWTBaVEUyTUdWaU1UVTROU0lzSW5SaFp5STZJaUo5	1752732701
\.


--
-- Data for Name: submenu_master; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.submenu_master (submenu_id, menu_id, submenu_name, url) FROM stdin;
3	2	Main Menu	/admin/menu-list
4	2	Sub Menu	/admin/submenu-list
5	2	Hc Payment Pararmeter	/admin/payment-parameter-list
6	2	Dc Payment Parameter	/admin/payment-parameter-list-dc
8	3	Role And Permission	/admin/roles
9	3	Highcourt User	/admin/hc-user-list
10	3	District Court User	/admin/dc-user-list
15	5	Rejected Application	/admin/hc-rejected-application
16	5	Paid Application	/admin/hc-paid-application
19	6	Rejected Application	/admin/dc-rejected-application
20	6	Paid Application	/admin/dc-paid-application
29	4	Delivered Applications	/admin/hc-web-delivered-application
12	4	All  Applications	/admin/hc-web-application
30	4	Pending Applications	/admin/hc-web-pending-application
31	5	Delivered Applications	/admin/hc-other-delivered-copy
32	5	Pending Applications	/admin/hc-other-pending-copy
14	5	All Applications	/admin/hc-other-copy
21	8	New Application	/admin/dc-web-application
33	8	Pending Applications	/admin/dc-web-pending-application
34	8	Delivered Applications	/admin/dc-web-deliver-application
36	6	Pending Applications	/admin/dc-other-pending-copy
37	6	Delivered Applications	/admin/dc-other-delivered-copy
17	6	All Application	/admin/dc-other-copy
38	11	Payment Report	/admin/payment-report
39	11	Delivered Report	/admin/delivered-report
40	11	Pending Report	/admin/pending-report
41	11	Activity Logs	/admin/activity-log-report
42	12	Payment Report	/admin/payment-report-dc
43	12	Delivered Report	/admin/delivered-report-dc
44	12	Pending Report	/admin/pending-report-dc
45	12	Activity Logs	/admin/activity-log-report-dc
46	2	Fee Master	/admin/fee-master
\.


--
-- Data for Name: table_log; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.table_log (id, table_name, operation_type, old_data, new_data, changed_at) FROM stdin;
1	hc_order_copy_applicant_registration	INSERT	\N	{"cino": "JHHC010130052015", "email": "a@gmail.com", "user_id": 0, "case_type": 57, "case_year": 2015, "applied_by": "respondent", "created_at": "2025-03-19T12:28:37.695008", "created_by": "system", "updated_at": "2025-03-19T12:28:37.695008", "updated_by": null, "case_number": "3743", "filing_year": 2015, "request_mode": "urgent", "filing_number": "24118", "mobile_number": "9090909090", "applicant_name": "Raushan Kumar", "application_id": 11, "deficit_amount": null, "deficit_status": 0, "payment_status": 0, "document_status": 0, "filingcase_type": 57, "petitioner_name": "BIKASH CHANDRA THAKUR", "respondent_name": "THE STATE OF JHARKHAND AND ANR", "application_number": "HCW05719032501", "deficit_payment_status": 0, "certified_copy_ready_status": 0, "advocate_registration_number": null}	2025-03-19 12:28:37.695008
2	hc_order_copy_applicant_registration	INSERT	\N	{"cino": "JHHC010130052015", "email": "a@gmail.com", "user_id": 0, "case_type": 57, "case_year": 2015, "applied_by": "petitioner", "created_at": "2025-03-20T15:16:56.08977", "created_by": "system", "updated_at": "2025-03-20T15:16:56.08977", "updated_by": null, "case_number": "3743", "filing_year": 2015, "request_mode": "urgent", "filing_number": "24118", "mobile_number": "9090909090", "applicant_name": "Raushan Kumar", "application_id": 12, "deficit_amount": null, "deficit_status": 0, "payment_status": 0, "document_status": 0, "filingcase_type": 57, "petitioner_name": "BIKASH CHANDRA THAKUR", "respondent_name": "THE STATE OF JHARKHAND AND ANR", "application_number": "HCW05720032501", "deficit_payment_status": 0, "certified_copy_ready_status": 0, "advocate_registration_number": null}	2025-03-20 15:16:56.08977
3	hc_order_copy_applicant_registration	UPDATE	{"cino": "JHHC010130052015", "email": "a@gmail.com", "user_id": 0, "case_type": 57, "case_year": 2015, "applied_by": "petitioner", "created_at": "2025-03-20T15:16:56.08977", "created_by": "system", "updated_at": "2025-03-20T15:16:56.08977", "updated_by": null, "case_number": "3743", "filing_year": 2015, "request_mode": "urgent", "filing_number": "24118", "mobile_number": "9090909090", "applicant_name": "Raushan Kumar", "application_id": 12, "deficit_amount": null, "deficit_status": 0, "payment_status": 0, "document_status": 0, "filingcase_type": 57, "petitioner_name": "BIKASH CHANDRA THAKUR", "respondent_name": "THE STATE OF JHARKHAND AND ANR", "application_number": "HCW05720032501", "deficit_payment_status": 0, "certified_copy_ready_status": 0, "advocate_registration_number": null}	{"cino": "JHHC010130052015", "email": "a@gmail.com", "user_id": 0, "case_type": 57, "case_year": 2015, "applied_by": "petitioner", "created_at": "2025-03-20T15:16:56.08977", "created_by": "system", "updated_at": "2025-03-20T15:23:02.648352", "updated_by": null, "case_number": "3743", "filing_year": 2015, "request_mode": "urgent", "filing_number": "24118", "mobile_number": "9090909090", "applicant_name": "Raushan Kumar", "application_id": 12, "deficit_amount": null, "deficit_status": 0, "payment_status": 0, "document_status": 1, "filingcase_type": 57, "petitioner_name": "BIKASH CHANDRA THAKUR", "respondent_name": "THE STATE OF JHARKHAND AND ANR", "application_number": "HCW05720032501", "deficit_payment_status": 0, "certified_copy_ready_status": 0, "advocate_registration_number": null}	2025-03-20 15:23:02.648352
4	hc_order_copy_applicant_registration	INSERT	\N	{"cino": "JHHC010130052015", "email": "a@gmail.com", "user_id": 0, "case_type": 57, "case_year": 2015, "applied_by": "petitioner", "created_at": "2025-03-25T17:23:22.821467", "created_by": "system", "updated_at": "2025-03-25T17:23:22.821467", "updated_by": null, "case_number": "3743", "filing_year": 2015, "request_mode": "urgent", "filing_number": "24118", "mobile_number": "9090909090", "applicant_name": "Raushan Kumar", "application_id": 13, "deficit_amount": null, "deficit_status": 0, "payment_status": 0, "document_status": 0, "filingcase_type": 57, "petitioner_name": "BIKASH CHANDRA THAKUR", "respondent_name": "THE STATE OF JHARKHAND AND ANR", "application_number": "HCW05725032501", "deficit_payment_status": 0, "certified_copy_ready_status": 0, "advocate_registration_number": null}	2025-03-25 17:23:22.821467
5	hc_order_copy_applicant_registration	INSERT	\N	{"cino": "JHHC010130052015", "email": "a@gmail.com", "user_id": 0, "case_type": 57, "case_year": 2015, "applied_by": "petitioner", "created_at": "2025-04-25T11:23:33.500829", "created_by": "system", "updated_at": "2025-04-25T11:23:33.500829", "updated_by": null, "case_number": "3743", "filing_year": 2015, "request_mode": "urgent", "filing_number": "24118", "mobile_number": "9090909090", "applicant_name": "Raushan Kumar", "application_id": 14, "deficit_amount": null, "deficit_status": 0, "payment_status": 0, "document_status": 0, "filingcase_type": 57, "petitioner_name": "BIKASH CHANDRA THAKUR", "respondent_name": "THE STATE OF JHARKHAND AND ANR", "application_number": "HCW05725042501", "deficit_payment_status": 0, "certified_copy_ready_status": 0, "advocate_registration_number": null}	2025-04-25 11:23:33.500829
\.


--
-- Data for Name: transaction_master_dc; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.transaction_master_dc (id, application_number, transaction_no, amount, urgent_fee, transaction_date, payment_status, transaction_status, paymentstatusmessage, depositer_id, deficit_payment, created_at, updated_at, double_verification, district_code, establishment_code) FROM stdin;
\.


--
-- Data for Name: transaction_master_hc; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.transaction_master_hc (id, application_number, transaction_no, amount, urgent_fee, transaction_date, payment_status, transaction_status, paymentstatusmessage, depositer_id, deficit_payment, created_at, updated_at, double_verification) FROM stdin;
168	HCW00314072501	TRN1407202510	15.00	5.00	\N	1	\N	NA	DRID001	0	2025-07-14 17:46:31.072693	2025-07-14 17:47:29.266801	0
169	HCW05715072501	TRN1507202501	25.00	5.00	\N	1	\N	NA	DRID001	0	2025-07-15 16:56:33.246243	2025-07-15 17:05:31.041432	0
170	HCW05715072502	TRN1507202502	20.00	5.00	\N	1	\N	NA	DRID001	0	2025-07-15 17:52:03.891761	2025-07-17 11:06:49.622108	0
171	HCW05715072502	TRN1707202501	45.00	0.00	\N	3	\N	NA	DRID001	0	2025-07-17 11:07:55.615005	2025-07-17 11:07:55.615005	0
172	HCW05717072501	TRN1707202502	15.00	5.00	\N	1	\N	NA	DRID001	0	2025-07-17 11:09:05.044642	2025-07-17 11:09:48.26949	0
\.


--
-- Data for Name: user_establishments; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.user_establishments (id, user_id, est_code, role_id, dist_code) FROM stdin;
29	7	JHRN03	7	364
30	7	JHRN04	7	364
31	7	JHRN02	7	364
32	7	JHRN05	7	364
33	7	JHRN01	7	364
34	6	JHRN03	6	364
35	6	JHRN04	6	364
36	6	JHRN02	6	364
37	6	JHRN05	6	364
38	6	JHRN01	6	364
39	8	JHRN03	6	364
40	8	JHRN04	6	364
41	8	JHRN02	6	364
42	8	JHRN05	6	364
43	8	JHRN01	6	364
44	9	JHBK04	6	355
45	9	JHBT04	6	355
46	9	JHBK02	6	355
47	9	JHBT02	6	355
48	9	JHBT01	6	355
\.


--
-- Name: civil_court_users_master_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.civil_court_users_master_id_seq', 9, true);


--
-- Name: civilcourt_applicant_document_detail_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.civilcourt_applicant_document_detail_id_seq', 23, true);


--
-- Name: dc_users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.dc_users_id_seq', 9, true);


--
-- Name: district_application_number_tracker_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.district_application_number_tracker_id_seq', 29, true);


--
-- Name: district_court_applicant_registration_application_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.district_court_applicant_registration_application_id_seq', 43, true);


--
-- Name: district_court_order_copy_applicant_registra_application_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.district_court_order_copy_applicant_registra_application_id_seq', 47, true);


--
-- Name: district_court_order_copy_application_number_tracker_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.district_court_order_copy_application_number_tracker_id_seq', 4, true);


--
-- Name: district_court_order_details_order_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.district_court_order_details_order_id_seq', 108, true);


--
-- Name: establishment_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.establishment_id_seq', 128, true);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: fee_master_fee_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.fee_master_fee_id_seq', 11, true);


--
-- Name: hc_order_copy_applicant_registration_application_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.hc_order_copy_applicant_registration_application_id_seq', 55, true);


--
-- Name: hc_order_details_order_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.hc_order_details_order_id_seq', 116, true);


--
-- Name: hc_users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.hc_users_id_seq', 5, true);


--
-- Name: high_court_applicant_registration_application_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.high_court_applicant_registration_application_id_seq', 46, true);


--
-- Name: high_court_users_master_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.high_court_users_master_id_seq', 1, false);


--
-- Name: highcourt_applicant_document_detail_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.highcourt_applicant_document_detail_id_seq', 19, true);


--
-- Name: jegras_merchant_details_dc_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.jegras_merchant_details_dc_id_seq', 24, true);


--
-- Name: jegras_merchant_details_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.jegras_merchant_details_id_seq', 1, true);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: log_activity_dc_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.log_activity_dc_id_seq', 25, true);


--
-- Name: log_activity_hc_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.log_activity_hc_id_seq', 67, true);


--
-- Name: menu_master_menu_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.menu_master_menu_id_seq', 12, true);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 3, true);


--
-- Name: role_master_role_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.role_master_role_id_seq', 1, false);


--
-- Name: role_permissions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.role_permissions_id_seq', 654, true);


--
-- Name: submenu_master_submenu_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.submenu_master_submenu_id_seq', 46, true);


--
-- Name: table_log_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.table_log_id_seq', 5, true);


--
-- Name: transaction_master_dc_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.transaction_master_dc_id_seq', 302, true);


--
-- Name: transaction_master_hc_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.transaction_master_hc_id_seq', 172, true);


--
-- Name: user_establishments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.user_establishments_id_seq', 48, true);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: high_court_case_type case_type_t_pkey1; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.high_court_case_type
    ADD CONSTRAINT case_type_t_pkey1 PRIMARY KEY (case_type);


--
-- Name: civil_court_users_master civil_court_users_master_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.civil_court_users_master
    ADD CONSTRAINT civil_court_users_master_email_key UNIQUE (email);


--
-- Name: civil_court_users_master civil_court_users_master_mobile_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.civil_court_users_master
    ADD CONSTRAINT civil_court_users_master_mobile_key UNIQUE (mobile_number);


--
-- Name: civil_court_users_master civil_court_users_master_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.civil_court_users_master
    ADD CONSTRAINT civil_court_users_master_pkey PRIMARY KEY (id);


--
-- Name: civil_court_users_master civil_court_users_master_user_name_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.civil_court_users_master
    ADD CONSTRAINT civil_court_users_master_user_name_key UNIQUE (user_name);


--
-- Name: civilcourt_applicant_document_detail civilcourt_applicant_document_detail_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.civilcourt_applicant_document_detail
    ADD CONSTRAINT civilcourt_applicant_document_detail_pkey PRIMARY KEY (id);


--
-- Name: dc_users dc_users_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dc_users
    ADD CONSTRAINT dc_users_email_key UNIQUE (email);


--
-- Name: dc_users dc_users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dc_users
    ADD CONSTRAINT dc_users_pkey PRIMARY KEY (id);


--
-- Name: dc_users dc_users_username_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.dc_users
    ADD CONSTRAINT dc_users_username_key UNIQUE (username);


--
-- Name: district_application_number_tracker district_application_number_tracker_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.district_application_number_tracker
    ADD CONSTRAINT district_application_number_tracker_pkey PRIMARY KEY (id);


--
-- Name: district_court_applicant_registration district_court_applicant_registration_application_number_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.district_court_applicant_registration
    ADD CONSTRAINT district_court_applicant_registration_application_number_key UNIQUE (application_number);


--
-- Name: district_court_applicant_registration district_court_applicant_registration_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.district_court_applicant_registration
    ADD CONSTRAINT district_court_applicant_registration_pkey PRIMARY KEY (application_id);


--
-- Name: district_court_order_copy_applicant_registration district_court_order_copy_applicant_regi_application_number_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.district_court_order_copy_applicant_registration
    ADD CONSTRAINT district_court_order_copy_applicant_regi_application_number_key UNIQUE (application_number);


--
-- Name: district_court_order_copy_applicant_registration district_court_order_copy_applicant_registration_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.district_court_order_copy_applicant_registration
    ADD CONSTRAINT district_court_order_copy_applicant_registration_pkey PRIMARY KEY (application_id);


--
-- Name: district_court_order_copy_application_number_tracker district_court_order_copy_application_number_tracker_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.district_court_order_copy_application_number_tracker
    ADD CONSTRAINT district_court_order_copy_application_number_tracker_pkey PRIMARY KEY (id);


--
-- Name: district_court_order_details district_court_order_details_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.district_court_order_details
    ADD CONSTRAINT district_court_order_details_pkey PRIMARY KEY (order_id);


--
-- Name: establishment establishment_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.establishment
    ADD CONSTRAINT establishment_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: fee_master fee_master_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.fee_master
    ADD CONSTRAINT fee_master_pkey PRIMARY KEY (fee_id);


--
-- Name: hc_order_copy_applicant_registration hc_order_copy_applicant_registration_application_number_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.hc_order_copy_applicant_registration
    ADD CONSTRAINT hc_order_copy_applicant_registration_application_number_key UNIQUE (application_number);


--
-- Name: hc_order_copy_applicant_registration hc_order_copy_applicant_registration_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.hc_order_copy_applicant_registration
    ADD CONSTRAINT hc_order_copy_applicant_registration_pkey PRIMARY KEY (application_id);


--
-- Name: hc_order_copy_application_number_tracker hc_order_copy_application_number_tracker_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.hc_order_copy_application_number_tracker
    ADD CONSTRAINT hc_order_copy_application_number_tracker_pkey PRIMARY KEY (date_key);


--
-- Name: hc_order_details hc_order_details_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.hc_order_details
    ADD CONSTRAINT hc_order_details_pkey PRIMARY KEY (order_id);


--
-- Name: hc_users hc_users_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.hc_users
    ADD CONSTRAINT hc_users_email_key UNIQUE (email);


--
-- Name: hc_users hc_users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.hc_users
    ADD CONSTRAINT hc_users_pkey PRIMARY KEY (id);


--
-- Name: hc_users hc_users_username_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.hc_users
    ADD CONSTRAINT hc_users_username_key UNIQUE (username);


--
-- Name: high_court_applicant_registration high_court_applicant_registration_application_number_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.high_court_applicant_registration
    ADD CONSTRAINT high_court_applicant_registration_application_number_key UNIQUE (application_number);


--
-- Name: high_court_applicant_registration high_court_applicant_registration_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.high_court_applicant_registration
    ADD CONSTRAINT high_court_applicant_registration_pkey PRIMARY KEY (application_id);


--
-- Name: high_court_application_number_tracker high_court_application_number_tracker_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.high_court_application_number_tracker
    ADD CONSTRAINT high_court_application_number_tracker_pkey PRIMARY KEY (date_key);


--
-- Name: high_court_transaction_number_tracker high_court_transaction_number_tracker_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.high_court_transaction_number_tracker
    ADD CONSTRAINT high_court_transaction_number_tracker_pkey PRIMARY KEY (date_key);


--
-- Name: high_court_users_master high_court_users_master_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.high_court_users_master
    ADD CONSTRAINT high_court_users_master_email_key UNIQUE (email);


--
-- Name: high_court_users_master high_court_users_master_mobile_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.high_court_users_master
    ADD CONSTRAINT high_court_users_master_mobile_key UNIQUE (mobile);


--
-- Name: high_court_users_master high_court_users_master_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.high_court_users_master
    ADD CONSTRAINT high_court_users_master_pkey PRIMARY KEY (id);


--
-- Name: high_court_users_master high_court_users_master_user_name_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.high_court_users_master
    ADD CONSTRAINT high_court_users_master_user_name_key UNIQUE (user_name);


--
-- Name: highcourt_applicant_document_detail highcourt_applicant_document_detail_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.highcourt_applicant_document_detail
    ADD CONSTRAINT highcourt_applicant_document_detail_pkey PRIMARY KEY (id);


--
-- Name: jegras_merchant_details_dc jegras_merchant_details_dc_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jegras_merchant_details_dc
    ADD CONSTRAINT jegras_merchant_details_dc_pkey PRIMARY KEY (id);


--
-- Name: jegras_merchant_details_hc jegras_merchant_details_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jegras_merchant_details_hc
    ADD CONSTRAINT jegras_merchant_details_pkey PRIMARY KEY (id);


--
-- Name: jegras_payment_request_dc jegras_payment_request_dc_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jegras_payment_request_dc
    ADD CONSTRAINT jegras_payment_request_dc_pkey PRIMARY KEY (depttranid);


--
-- Name: jegras_payment_request_hc jegras_payment_request_hc_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jegras_payment_request_hc
    ADD CONSTRAINT jegras_payment_request_hc_pkey PRIMARY KEY (depttranid);


--
-- Name: jegras_payment_response_dc jegras_payment_response_dc_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jegras_payment_response_dc
    ADD CONSTRAINT jegras_payment_response_dc_pkey PRIMARY KEY (depttranid);


--
-- Name: jegras_payment_response_hc jegras_payment_response_hc_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jegras_payment_response_hc
    ADD CONSTRAINT jegras_payment_response_hc_pkey PRIMARY KEY (depttranid);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: log_activity_dc log_activity_dc_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.log_activity_dc
    ADD CONSTRAINT log_activity_dc_pkey PRIMARY KEY (id);


--
-- Name: log_activity_hc log_activity_hc_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.log_activity_hc
    ADD CONSTRAINT log_activity_hc_pkey PRIMARY KEY (id);


--
-- Name: menu_master menu_master_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.menu_master
    ADD CONSTRAINT menu_master_pkey PRIMARY KEY (menu_id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: role_master role_master_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_master
    ADD CONSTRAINT role_master_pkey PRIMARY KEY (role_id);


--
-- Name: role_permissions role_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_permissions
    ADD CONSTRAINT role_permissions_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: submenu_master submenu_master_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.submenu_master
    ADD CONSTRAINT submenu_master_pkey PRIMARY KEY (submenu_id);


--
-- Name: table_log table_log_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.table_log
    ADD CONSTRAINT table_log_pkey PRIMARY KEY (id);


--
-- Name: transaction_master_dc transaction_master_dc_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transaction_master_dc
    ADD CONSTRAINT transaction_master_dc_pkey PRIMARY KEY (id);


--
-- Name: transaction_master_hc transaction_master_hc_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transaction_master_hc
    ADD CONSTRAINT transaction_master_hc_pkey PRIMARY KEY (id);


--
-- Name: user_establishments user_establishments_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_establishments
    ADD CONSTRAINT user_establishments_pkey PRIMARY KEY (id);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: district_court_order_copy_applicant_registration set_updated_at; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER set_updated_at BEFORE UPDATE ON public.district_court_order_copy_applicant_registration FOR EACH ROW EXECUTE FUNCTION public.update_updated_at_column();


--
-- Name: hc_order_copy_applicant_registration set_updated_at; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER set_updated_at BEFORE UPDATE ON public.hc_order_copy_applicant_registration FOR EACH ROW EXECUTE FUNCTION public.update_updated_at_column();


--
-- Name: jegras_payment_request_dc trigger_update_updated_at; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trigger_update_updated_at BEFORE UPDATE ON public.jegras_payment_request_dc FOR EACH ROW EXECUTE FUNCTION public.update_updated_at_column();


--
-- Name: jegras_payment_request_hc trigger_update_updated_at; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trigger_update_updated_at BEFORE UPDATE ON public.jegras_payment_request_hc FOR EACH ROW EXECUTE FUNCTION public.update_updated_at_column();


--
-- Name: transaction_master_dc trigger_update_updated_at; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trigger_update_updated_at BEFORE UPDATE ON public.transaction_master_dc FOR EACH ROW EXECUTE FUNCTION public.update_updated_at_column();


--
-- Name: transaction_master_hc trigger_update_updated_at; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trigger_update_updated_at BEFORE UPDATE ON public.transaction_master_hc FOR EACH ROW EXECUTE FUNCTION public.update_updated_at_column();


--
-- Name: role_permissions role_permissions_menu_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_permissions
    ADD CONSTRAINT role_permissions_menu_id_fkey FOREIGN KEY (menu_id) REFERENCES public.menu_master(menu_id) ON DELETE CASCADE;


--
-- Name: role_permissions role_permissions_submenu_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_permissions
    ADD CONSTRAINT role_permissions_submenu_id_fkey FOREIGN KEY (submenu_id) REFERENCES public.submenu_master(submenu_id) ON DELETE CASCADE;


--
-- Name: user_establishments user_establishments_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.user_establishments
    ADD CONSTRAINT user_establishments_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.dc_users(id) ON DELETE CASCADE;


--
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: pg_database_owner
--

GRANT ALL ON SCHEMA public TO postgres;


--
-- PostgreSQL database dump complete
--


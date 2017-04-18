drop table if exists aulas cascade;

create table aulas
(
    id       bigserial    constraint pk_aulas primary key,
    den_aula varchar(255) not null
);

insert into aulas (den_aula)
    values ('P7'),
           ('P10');

drop table if exists ordenadores cascade;

create table ordenadores
(
    id         bigserial     constraint pk_ordenadores primary key,
    marca_ord  varchar(255),
    modelo_ord varchar(255),
    aula_id    bigint        not null constraint fk_ordenadores_aulas
                             references aulas (id)
                             on delete no action on update cascade
);

insert into ordenadores (marca_ord, modelo_ord, aula_id)
    values ('Sony', 'Vaio', 1),
           ('IBM', '8088', 2);

drop table if exists dispositivos cascade;

create table dispositivos
(
    id           bigserial     constraint pk_dispositivos primary key,
    marca_disp   varchar(255),
    modelo_disp  varchar(255),
    ordenador_id bigint        constraint fk_dispositivos_ordenadores
                               references ordenadores (id)
                               on delete no action on update cascade,
    aula_id      bigint        constraint fk_dispositivos_aulas
                               references aulas (id)
                               on delete no action on update cascade,
    constraint ck_lugar_valido check ((ordenador_id is null and aula_id is not null)
                                   or (ordenador_id is not null and aula_id is null))
);

insert into dispositivos (marca_disp, modelo_disp, ordenador_id, aula_id)
    values ('Seagate', 'Barracuda 1TB', 1, null),
           ('Logitech', 'K120', 2, null),
           ('Ratón', 'Superguay', 2, null),
           ('nVIDIA', 'GTX480', null, 2);

drop table if exists registro_ord cascade;

create table registro_ord
(
    id           bigserial   constraint pk_registro_ord primary key,
    ordenador_id bigint      not null constraint fk_registro_ord_ordenadores
                                      references ordenadores (id)
                                      on delete no action on update cascade,
    origen_id    bigint      not null constraint fk_registro_ord_origen
                                      references aulas (id)
                                      on delete no action on update cascade,
    destino_id   bigint      not null constraint fk_registro_ord_destino
                                      references aulas (id)
                                      on delete no action on update cascade,
    created_at   timestamptz not null default current_timestamp
);

drop table if exists registro_disp cascade;

create table registro_disp
(
    id              bigserial   constraint pk_registro_disp primary key,
    dispositivo_id  bigint      not null constraint fk_registro_disp_dispositivos
                                         references dispositivos (id)
                                         on delete no action on update cascade,
    origen_ord_id   bigint      references ordenadores (id),
    origen_aula_id  bigint      references aulas (id),
    destino_ord_id  bigint      references ordenadores (id),
    destino_aula_id bigint      references aulas (id),
    created_at      timestamptz not null default current_timestamp,
    check ((origen_ord_id is null and origen_aula_id is not null) or
           (origen_ord_id is not null and origen_aula_id is null)),
    check ((destino_ord_id is null and destino_aula_id is not null) or
           (destino_ord_id is not null and destino_aula_id is null))
);

create view v_dispositivos as
   select d.*,
          coalesce(
              a.den_aula,
              coalesce(o.marca_ord, '') || ' ' || coalesce(o.modelo_ord, '')
          ) as ubicacion
     from dispositivos d
left join aulas a on d.aula_id = a.id
left join ordenadores o on d.ordenador_id = o.id;

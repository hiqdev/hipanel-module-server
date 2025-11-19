type TwoToTen = 2 | 3 | 4 | 5 | 6 | 7 | 8 | 9 | 10;

type MakeIdPort<P extends string> =
  { [S in "" | `${TwoToTen}` as `${P}${S extends "" ? "" : S}_id`]?: string | null } &
  { [S in "" | `${TwoToTen}` as `${P}${S extends "" ? "" : S}_port`]?: string | null };

export type AssignHubs = MakeIdPort<"net"> & MakeIdPort<"pdu"> & {
  net_id?: string | null;
  net_port?: string | null;
  pdu_id?: string | null;
  pdu_port?: string | null;
  kvm_id?: string | null;
  kvm_port?: string | null;
  rack_id?: string | null;
  rack_port?: string | null;
  location_id?: string | null;
  ipmi_id?: string | null;
  ipmi_port?: string | null;
  jbod_id?: string | null;
  jbod_port?: string | null;
};

export interface Server {
  serverName?: string | null;
  dc?: string | null;
  type?: string | null;
  status?: string | null;
  order?: string | null;
  internalNote?: string | null;
  hardwareSummary?: string | null;
  hardwareComment?: string | null;
}

export interface Hub {
  name?: string | null;
  inn?: string | null;
  type?: string | null;
  mac?: string | null;
  ip?: string | null;
  model?: string | null;
  order_no?: string | null;
  note?: string | null;
}

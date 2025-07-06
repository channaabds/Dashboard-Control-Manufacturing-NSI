import getPercenQuality from "./getPercenQuality"

export default async function getDataQuality() {
  try {
    const res = await fetch('http://192.168.10.75:5000/api/quality/report', {next: {revalidate: 0}})

    if (!res.ok) {
      throw new Error('failed to fetch')
    }

    const dataApi = await res.json()
    const { data } = dataApi.payload
    return data
  } catch (error) {
    console.error(error)
    return [
      {
        target_cam_ipqc: 1,
        target_cnc_ipqc: 1,
        target_mfg_ipqc: 1,
        target_cam_oqc: 1,
        target_cnc_oqc: 1,
        target_mfg_oqc: 1,
        ncr_cam_ipqc: 0,
        lot_cam_ipqc: 0,
        ncr_cnc_ipqc: 0,
        lot_cnc_ipqc: 0,
        ncr_mfg_ipqc: 0,
        lot_mfg_ipqc: 0,
        ncr_cam_oqc: 0,
        lot_cam_oqc: 0,
        ncr_cnc_oqc: 0,
        lot_cnc_oqc: 0,
        ncr_mfg_oqc: 0,
        lot_mfg_oqc: 0,
        date: "2023-11-01"
      }
    ]
  }
}

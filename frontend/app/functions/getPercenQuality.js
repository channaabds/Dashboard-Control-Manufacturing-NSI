export default function getPercenQuality(data) {
  try {
    let percentCamIpqc;
    let percentCncIpqc;
    let percentMfgIpqc;
    let percentCamOqc;
    let percentCncOqc;
    let percentMfgOqc;

    data.map((element) => {
      percentCamIpqc = ((element.ncr_cam_ipqc + element.lot_cam_ipqc) / element.target_cam_ipqc) * 100;
      percentCncIpqc = ((element.ncr_cnc_ipqc + element.lot_cnc_ipqc) / element.target_cnc_ipqc) * 100;
      percentMfgIpqc = ((element.ncr_mfg_ipqc + element.lot_mfg_ipqc) / element.target_mfg_ipqc) * 100;
      percentCamOqc = ((element.ncr_cam_oqc + element.lot_cam_oqc) / element.target_cam_oqc) * 100;
      percentCncOqc = ((element.ncr_cnc_oqc + element.lot_cnc_oqc) / element.target_cnc_oqc) * 100;
      percentMfgOqc = ((element.ncr_mfg_oqc + element.lot_mfg_oqc) / element.target_mfg_oqc) * 100;
    })

    const result = {
      percentCamIpqc,
      percentCncIpqc,
      percentMfgIpqc,
      percentCamOqc,
      percentCncOqc,
      percentMfgOqc,
    }

    return result;
  } catch (error) {
    console.error(error)
    return {
      percentCamIpqc: 0,
      percentCncIpqc: 0,
      percentMfgIpqc: 0,
      percentCamOqc: 0,
      percentCncOqc: 0,
      percentMfgOqc: 0,
    }
  }
}

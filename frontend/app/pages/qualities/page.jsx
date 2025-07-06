import HeaderSection from "@/app/components/header/sectionHeader";
import getDataQuality from "@/app/functions/getDataQuality";
import React from "react";

export default async function Quality() {
  const data = await getDataQuality();
  const value = data[0];
  return (
    <>
      <div className="flex flex-col gap-[20px] m-[20px] w-full h-full">
        <div className="bg-[#D9D9D9] w-full h-full rounded-lg flex flex-col p-[20px]">
          <div className="text-[40px] w-full p-[15px] bg-[#fcfcfc] rounded-xl">
            <HeaderSection name="IPQC" />
            <table className="border border-collapse text-[40px] text-center bg-[#9DA5EE] w-full mt-[20px]">
              <thead>
                <tr>
                  <td className="border border-slate-300">Department</td>
                  <td className="border border-slate-300">Target</td>
                  <td className="border border-slate-300">NCR</td>
                  <td className="border border-slate-300">Lot Tag</td>
                  <td className="border border-slate-300">Total Aktual</td>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td className="border border-slate-300">CAM</td>
                  <td className="border border-slate-300">{value.target_cam_ipqc}</td>
                  <td className="border border-slate-300">{value.ncr_cam_ipqc}</td>
                  <td className="border border-slate-300">{value.lot_cam_ipqc}</td>
                  <td className="border border-slate-300">{value.ncr_cam_ipqc + value.lot_cam_ipqc}</td>
                </tr>
                <tr>
                  <td className="border border-slate-300">CNC</td>
                  <td className="border border-slate-300">{value.target_cnc_ipqc}</td>
                  <td className="border border-slate-300">{value.ncr_cnc_ipqc}</td>
                  <td className="border border-slate-300">{value.lot_cnc_ipqc}</td>
                  <td className="border border-slate-300">{value.ncr_cnc_ipqc + value.lot_cnc_ipqc}</td>
                </tr>
                <tr>
                  <td className="border border-slate-300">MFG2</td>
                  <td className="border border-slate-300">{value.target_mfg_ipqc}</td>
                  <td className="border border-slate-300">{value.ncr_mfg_ipqc}</td>
                  <td className="border border-slate-300">{value.lot_mfg_ipqc}</td>
                  <td className="border border-slate-300">{value.ncr_mfg_ipqc + value.lot_mfg_ipqc}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div className="bg-[#D9D9D9] w-full h-full rounded-lg flex flex-col p-[20px]">
          <div className="text-[40px] w-full p-[15px] bg-[#fcfcfc] rounded-xl">
            <HeaderSection name="OQC" />
            <table className="border border-collapse text-[40px] text-center bg-[#9DA5EE] w-full mt-[20px]">
              <thead>
                <tr>
                  <td className="border border-slate-300">Department</td>
                  <td className="border border-slate-300">Target</td>
                  <td className="border border-slate-300">NCR</td>
                  <td className="border border-slate-300">Lot Tag</td>
                  <td className="border border-slate-300">Total Aktual</td>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td className="border border-slate-300">CAM</td>
                  <td className="border border-slate-300">{value.target_cam_oqc}</td>
                  <td className="border border-slate-300">{value.ncr_cam_oqc}</td>
                  <td className="border border-slate-300">{value.lot_cam_oqc}</td>
                  <td className="border border-slate-300">{value.ncr_cam_oqc + value.lot_cam_oqc}</td>
                </tr>
                <tr>
                  <td className="border border-slate-300">CNC</td>
                  <td className="border border-slate-300">{value.target_cnc_oqc}</td>
                  <td className="border border-slate-300">{value.ncr_cnc_oqc}</td>
                  <td className="border border-slate-300">{value.lot_cnc_oqc}</td>
                  <td className="border border-slate-300">{value.ncr_cnc_oqc + value.lot_cnc_oqc}</td>
                </tr>
                <tr>
                  <td className="border border-slate-300">MFG2</td>
                  <td className="border border-slate-300">{value.target_mfg_oqc}</td>
                  <td className="border border-slate-300">{value.ncr_mfg_oqc}</td>
                  <td className="border border-slate-300">{value.lot_mfg_oqc}</td>
                  <td className="border border-slate-300">{value.ncr_mfg_oqc + value.lot_mfg_oqc}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </>
  );
}

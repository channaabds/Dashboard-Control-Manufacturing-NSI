import HeaderSection from "@/app/components/header/sectionHeader";
import getDataTargetMonthly from "@/app/functions/getDataTargetMonthly";
import React from "react";
import ListCustomer from "../components/listCustomer";

async function getDataCustomer() {
  const res = await fetch("http://192.168.10.75:5000/api/sales/customer", {
    next: { revalidate: 0 },
  });

  if (!res.ok) {
    throw new Error("failed to fetch data");
  }

  return res.json();
}

export default async function Sales(props) {
  const dataApi = await getDataCustomer();
  const targetMonthly = await getDataTargetMonthly();
  const data = dataApi.payload.data;
  let totalTarget = 0.0;
  let totalAktual = 0.0;
  let bulan = "default";

  data.map((e) => {
    totalTarget += e.totalTargetUSD;
    totalAktual += e.totalAktualUSD;
    bulan = e.bulan;
  });
  let persentaseTA = (totalAktual / totalTarget) * 100;

  let USDollar = new Intl.NumberFormat("en-US", {
    style: "currency",
    currency: "USD",
  });

  return (
    <div className="bg-[#D9D9D9] w-full h-full rounded-lg flex flex-col justify-center items-center gap-[38px]">
        <HeaderSection name={`Sales Bulan ${bulan}`} />
        <div className="col h-full w-full flex flex-col gap-1 p-2">
          <div className="col h-[80px] flex flex-row rounded bg-[#9DA5EE] justify-center">
            <div className="basis-1/4 flex justify-center items-center h-full text-[48px]">
              Nama Customer
            </div>
            <div className="basis-1/4 flex justify-center items-center h-full text-[48px]">
              Aktual Qty
            </div>
            <div className="basis-1/4 flex justify-center items-center h-full text-[48px]">
              Aktual Sales
            </div>
            <div className="basis-1/4 flex justify-center items-center h-full text-[48px]">
              persentase
            </div>
          </div>
          <div className="col h-[530px] overflow-y-auto">
            <ListCustomer />
          </div>
          <div className="col h-[100px] flex items-center justify-end gap-10 px-8 mt-[50px]">
            <div className="w-full text-center bg-[#9DA5EE] p-2 text-[40px] rounded">
              <HeaderSection name={"TARGET BY GM"} />
              {USDollar.format(targetMonthly)}
            </div>
            <div className="w-full text-center bg-[#9DA5EE] p-2 text-[40px] rounded">
              <HeaderSection name={"total aktual sales"} />
              {USDollar.format(totalAktual.toFixed(2))}
            </div>
            <div
              className={`w-full text-center p-2 text-[45px] rounded ${
                persentaseTA < 85 ? "bg-[#FF0000] " : "bg-[#05A305] "
              }`}
            >
              <HeaderSection name={"persentase by SO"} />
              {persentaseTA.toFixed(2)} %
            </div>
          </div>
        </div>
    </div>
  );
}

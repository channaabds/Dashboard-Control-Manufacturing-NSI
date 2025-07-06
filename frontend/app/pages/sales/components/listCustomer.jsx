import Link from "next/link";
import React from "react";

async function getDataCustomer() {
  const res = await fetch(`http://192.168.10.75:5000/api/sales/customer`, {
    next: { revalidate: 0 },
  });

  if (!res.ok) {
    throw new Error("failed to fetch data");
  }

  return res.json();
}

export default async function ListCustomer() {
  const dataApi = await getDataCustomer();
  const data = dataApi.payload.data;
  let pembilang = 0.0001;
  let penyebut = 0.0001;
  let url = "smtg"
  let Qty = 0.0001
  let USDollar = new Intl.NumberFormat("en-US", {
    style: "currency",
    currency: "USD",
  });

  {data.map((e) => {
    penyebut += e.totalAktualUSD;
  })}

  return (
    <main>
      {data.map((e) => {
        url = e.namaCustomer;
        Qty = e.totalAktualQuantity
        pembilang = e.totalAktualUSD
        return (
          <div key={e.namaCustomer} className="bg-red-300 h-[80px] p-3 mt-3 rounded flex flex-row justify-center items-center gap-3">
            <div className="w-full h-full rounded bg-white text-[40px] flex justify-center items-center text-center">
              {url}
            </div>
            <div className="w-full h-full rounded bg-white text-[40px] flex justify-center items-center">
              {Qty.toLocaleString()}
            </div>
            <div className="w-full h-full rounded bg-white text-[40px] flex justify-center items-center">
              {USDollar.format(pembilang.toFixed(2))}
            </div>
            <div className="w-full h-full text-[40px] flex justify-center items-center rounded bg-white">
              {((pembilang / penyebut) * 100).toFixed(2)}%
            </div>
          </div>
        );
      })}
    </main>
  );
}

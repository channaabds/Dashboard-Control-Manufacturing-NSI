import React from 'react'

async function getListProdMachine(url) {
  const res = await fetch(`http://192.168.10.75:5000/api/production/line/${url}`, {next: {revalidate: 0}})
  if (!res.ok) {
    throw new Error('failed to fetch data')
  }
  return res.json()
}

export default async function ListProdMachine({ url }) {
  let minimum = 90
  if (url == 'CAM') {
    minimum = 80
  }
  let dataApi = await getListProdMachine(url)
  let { data } = dataApi.payload

  if (data.length == 1) {
    return (
      <>
        <div className="bg-red-300 h-[80px] p-3 mt-3 rounded flex justify-center items-center gap-3">
          <div className="h-full rounded bg-white text-[40px] flex justify-center items-center px-10">Maaf, belum ada lot yang sudah dibuat hari ini</div>
        </div>
      </>
    )
  }
  return (
    <>
      {data.map((element) => (
        <div key={element.id} className="bg-red-300 h-[80px] p-3 mt-3 rounded flex flex-row justify-center items-center gap-3">
          <div className="basis-1/5 h-full rounded bg-white text-[40px] flex justify-center items-center">{element.mcn}</div>
          <div className="basis-1/5 h-full rounded bg-white text-[32px] flex justify-center items-center">{element.itemCode}</div>
          <div className="basis-1/5 h-full rounded bg-white text-[40px] flex justify-center items-center">{element.planQty}</div>
          <div className="basis-1/5 h-full rounded bg-white text-[40px] flex justify-center items-center">{element.receiveQty}</div>
          <div className={`basis-1/5 h-full rounded ${element.percen > minimum  ? 'bg-[#05A305]' : 'bg-[#FF0000]'} text-[40px] flex justify-center items-center`}>
            <span className="text-stroke">
              <p className="text-[40px] text-white">{(element.percen).toFixed(2)} %</p>
            </span>
          </div>
        </div>
      ))}
    </>
  )
}

import React from 'react'

async function getDataMachinesDown() {
    const res = await fetch('http://192.168.10.75:5000/api/maintenance/data-target', {next: {revalidate: 0}})

    if (!res.ok) {
        throw new Error('Failed to fetch data')
    }

    return res.json()
}

export default async function ListMachine1() {
    const dataApi = await getDataMachinesDown()
    let data = dataApi.payload.data
  return (
    <>
        {data.map((element) => (
          <div key={element.totalDowntimeInHours} className="bg-[#ADBDC3] h-[60px] p-3 mt-3 rounded flex flex-row justify-center items-center gap-3">
            <div className="basis-1/4 h-full rounded text-[40px] flex justify-center items-center">{element.noMesin}</div>
            <div className="basis-2/4 h-full rounded text-[40px] flex justify-center items-center">{element.tglKerusakan}</div>
            <div className='basis-1/4 h-full rounded text-[40px] flex justify-center items-center'>{element.totalDowntimeInHours} JAM</div>
          </div>
        ))}
    </>
  )
}

import React from 'react'

async function getHistoryLine() {
  const res = await fetch('http://192.168.10.75:5000/api/production/history', {next: {revalidate: 0}})

  if (!res.ok) {
    throw new Error('failed to fetch data')
  }

  return res.json()
}

export default async function HistoryLine() {
  const dataApi = await getHistoryLine()
  const data = dataApi.payload.data
  return (
    <>
      {data.map((element) => {
        return (
          <div key={element.tgl} className="bg-red-300 h-[80px] p-3 mt-3 rounded flex flex-row justify-end items-center gap-3">
            {element.data.map((e) => {
              const bg = e.LineType == 'CAM' ? e.RataRata <= 60 ? 'bg-[#FF0000]' : e.RataRata > 60 && e.RataRata <= 80 ? 'bg-[#FF9900]' : 'bg-[#05A305]' : e.RataRata <= 70 ? 'bg-[#FF0000]' : e.RataRata > 70 && e.RataRata <= 85 ? 'bg-[#FF9900]' : 'bg-[#05A305]'
              return (
                <div key={e.RataRata} className={`basis-1/5 h-full rounded ${bg} text-[40px] flex justify-center items-center`} >
                  <span className="text-stroke">
                    <p className="text-[40px] text-white">{e.RataRata.toFixed(2)} %</p>
                  </span>
                </div>
              )
            })}
                <div className="basis-1/5 h-full rounded bg-white text-[40px] flex justify-center items-center">{element.tgl}</div>
          </div>
        )
      })}
    </>
  )
}

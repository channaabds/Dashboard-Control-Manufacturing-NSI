import React from 'react'

async function getDataMachinesDown() {
    const res = await fetch('http://192.168.10.75:5000/api/maintenance/history-downtime', {next: {revalidate: 0}})

    if (!res.ok) {
        throw new Error('Failed to fetch data')
    }

    return res.json()
}
export default async function DataMesin() {
  const dataApi = await getDataMachinesDown()
  let data = dataApi.payload.data

  return (
      <>
          {data.map((element) => {
              let textColorClass = 'text-green-500'; // nguah ke ijo

              if (element.totalDowntimeInHours >= 4125) {
                  textColorClass = 'text-red-500';
              } else if (element.totalDowntimeInHours > 2750 && element.totalDowntimeInHours < 4125) {
                  textColorClass = 'text-yellow-500';
              }

              return (
                  <div key={element.totalDowntimeInHours} className="bg-[#ADBDC3] h-[60px] p-3 mt-3 rounded flex flex-row justify-center items-center gap-1">
                      <div className="basis-1/2 h-full rounded text-[40px] flex justify-center items-center">{`${2750} Jam`}</div>
                      <div className="basis-1/2 h-full rounded text-[40px] flex justify-center items-center">{element.bulanDowntime}</div>
                      <div className={`basis-1/2 h-full rounded text-[40px] flex justify-center items-center ${textColorClass}`}>
                          {element.totalDowntimeInHours} JAM
                      </div>
                  </div>
              );
          })}
      </>
  )
}



// export default async function DataMesin() {
//     const dataApi = await getDataMachinesDown()
//     let data = dataApi.payload.data
//   return (
//     <>
//         {data.map((element) => (
//           <div key={element.totalDowntimeInHours} className="bg-[#ADBDC3] h-[60px] p-3 mt-3 rounded flex flex-row justify-center items-center gap-1">
//             <div className="basis-1/2 h-full rounded text-[40px] flex justify-center items-center">{`${2750} Jam`}</div>
//             <div className="basis-1/2 h-full rounded text-[40px] flex justify-center items-center">{element.bulanDowntime}</div>
//             <div className='basis-1/2 h-full rounded text-[40px] flex justify-center items-center'>{element.totalDowntimeInHours} JAM</div>
//           </div>
//         ))}
//     </>
//   )
// }

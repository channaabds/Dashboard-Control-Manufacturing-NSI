'use client'

import React from 'react'
import HeaderSection from '@/app/components/header/sectionHeader'
import ListProdMachine from './components/listProdMachine'

export default function DetailProduction(props) {
  const { line, percen } = props.searchParams
  let minimum = 90
  if (line == 'CAM') {
      minimum = 80
  }
  const value = parseFloat(percen)

  return (
    <main className='bg-[#D9D9D9] w-full h-full rounded-lg flex flex-col justify-center items-center gap-[38px]'>
        <HeaderSection name={`DATA PRODUKSI LINE ${line}`} />
        <div className="col h-full w-full flex flex-col gap-1 p-2">
            <div className='col h-[80px] flex flex-row rounded bg-[#9DA5EE] justify-center'>
                <div className="basis-1/4 flex justify-center items-center h-full text-[48px]">No Mesin</div>
                <div className="basis-1/4 flex justify-center items-center h-full text-[48px]">Item Code</div>
                <div className="basis-1/4 flex justify-center items-center h-full text-[48px]">Plan QTY</div>
                <div className="basis-1/4 flex justify-center items-center h-full text-[48px]">Receive Qty</div>
                <div className="basis-1/4 flex justify-center items-center h-full text-[48px]">Percen</div>
            </div>
            <div className="col h-[620px] overflow-y-auto">
              <ListProdMachine url={line} />
                {/* <ListProduksiMesin url={line} /> */}
            </div>
            <div className="col h-[100px] flex items-center justify-end gap-10 px-8">
                <p className='bg-[#9DA5EE] p-2 text-[40px] rounded'>PRESENTASE PRODUKSI LINE {line}</p>
                <p className={`${percen > minimum  ? 'bg-[#05A305]' : 'bg-[#FF0000]'} p-2 text-[48px] rounded`}><span className='text-stroke'>{(value).toFixed(2)} %</span></p>
            </div>
        </div>
    </main>
  )
}

import React from 'react'

export default function HeaderSection({ name }) {
  return (
    <div className="col h-[50px]">
      <div className="text-center">
        <p className="text-[48px]">{name}</p>
      </div>
    </div>
  )
}

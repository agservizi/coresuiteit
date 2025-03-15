"use client"

import * as React from "react"

import { cn } from "@/lib/utils"

const Table = React.forwardRef<HTMLTableElement, React.HTMLAttributes<HTMLTableElement>>(
  ({ className, ...props }, ref) => (
    <div className="w-full overflow-auto">
      <table ref={ref} className={cn("w-full caption-bottom text-sm", className)} {...props} />
    </div>
  ),
)
Table.displayName = "Table"

const TableHeader = React.forwardRef<HTMLTableSectionElement, React.HTMLAttributes<HTMLTableSectionElement>>(
  ({ className, ...props }, ref) => <thead ref={ref} className={cn("[&_tr]:border-b", className)} {...props} />,
)
TableHeader.displayName = "TableHeader"

const TableBody = React.forwardRef<HTMLTableSectionElement, React.HTMLAttributes<HTMLTableSectionElement>>(
  ({ className, ...props }, ref) => (
    <tbody ref={ref} className={cn("[&_tr:last-child]:border-0", className)} {...props} />
  ),
)
TableBody.displayName = "TableBody"

const TableFooter = React.forwardRef<HTMLTableSectionElement, React.HTMLAttributes<HTMLTableSectionElement>>(
  ({ className, ...props }, ref) => (
    <tfoot ref={ref} className={cn("border-t bg-muted/50 font-medium [&>tr]:last:border-b-0", className)} {...props} />
  ),
)
TableFooter.displayName = "TableFooter"

const TableRow = React.forwardRef<HTMLTableRowElement, React.HTMLAttributes<HTMLTableRowElement>>(
  ({ className, ...props }, ref) => (
    <tr
      ref={ref}
      className={cn("border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted", className)}
      {...props}
    />
  ),
)
TableRow.displayName = "TableRow"

// Modifica al componente TableHead per supportare la formattazione delle date
// Aggiungiamo il supporto per il dataType e dateFormat

const TableHead = React.forwardRef<
  HTMLTableCellElement,
  React.ThHTMLAttributes<HTMLTableCellElement> & {
    dataType?: "date" | "text" | "number"
    dateFormat?: string
  }
>(({ className, dataType, dateFormat = "dd-MM-yyyy", ...props }, ref) => (
  <th
    ref={ref}
    className={cn(
      "h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0",
      dataType === "date" && "date-column",
      className,
    )}
    data-date-format={dataType === "date" ? dateFormat : undefined}
    {...props}
  />
))
TableHead.displayName = "TableHead"

// Modifica al componente TableCell per formattare le date
const TableCell = React.forwardRef<
  HTMLTableCellElement,
  React.TdHTMLAttributes<HTMLTableCellElement> & {
    dataType?: "date" | "text" | "number"
  }
>(({ className, dataType, children, ...props }, ref) => {
  // Format date if the cell contains a date and the content is a valid date string
  let formattedContent = children

  if (dataType === "date" && children && typeof children === "string") {
    try {
      const dateObj = new Date(children)
      if (!isNaN(dateObj.getTime())) {
        const day = String(dateObj.getDate()).padStart(2, "0")
        const month = String(dateObj.getMonth() + 1).padStart(2, "0")
        const year = dateObj.getFullYear()
        formattedContent = `${day}-${month}-${year}`
      }
    } catch (e) {
      // If date parsing fails, use the original content
      console.error("Error formatting date:", e)
    }
  }

  return (
    <td ref={ref} className={cn("p-4 align-middle [&:has([role=checkbox])]:pr-0", className)} {...props}>
      {formattedContent}
    </td>
  )
})
TableCell.displayName = "TableCell"

const TableCaption = React.forwardRef<HTMLTableCaptionElement, React.HTMLAttributes<HTMLTableCaptionElement>>(
  ({ className, ...props }, ref) => (
    <caption ref={ref} className={cn("mt-4 text-sm text-muted-foreground", className)} {...props} />
  ),
)
TableCaption.displayName = "TableCaption"

export { Table, TableHeader, TableBody, TableFooter, TableHead, TableRow, TableCell, TableCaption }

